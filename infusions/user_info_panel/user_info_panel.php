<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_info_panel.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }


if (iMEMBER) {
	$msg_settings = dbarray(dbquery("SELECT * FROM ".DB_MESSAGES_OPTIONS." WHERE user_id='0'"));
	
	if (iADMIN || $userdata['user_id'] == 1) {
		$msg_settings['pm_inbox'] = 0;
	}
	
	if ($msg_settings['pm_inbox'] > 0)
	{
		$bdata = dbarray(dbquery(
			"SELECT COUNT(IF(message_folder=0, 1, null)) inbox_total,
			COUNT(IF(message_folder=1, 1, null)) outbox_total, COUNT(IF(message_folder=2, 1, null)) archive_total
			FROM ".DB_MESSAGES." WHERE message_to='".$userdata['user_id']."' GROUP BY message_to"
		));

		if (($msg_settings['pm_inbox'] - $bdata['inbox_total']) <= 5)
		{
			$inbox_full = $msg_settings['pm_inbox'] - $bdata['inbox_total'];
		}
	}
	
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder='0'");
	if (iADMIN)
	{
		$report_count1 = dbrows(dbquery('SELECT report_zap FROM '.DB_PREFIX.'reports WHERE report_zap < 1'));
	}
	
	if ((iADMIN && $report_count1) || $msg_count || isset($inbox_full))
	{
		openside('Se her!');
		if (iADMIN && $report_count1)
		{
			echo "<br />\n";
			
			if ($report_count1)
			{
				echo "<div style='text-align:center;margin-top:15px;'>\n";
				echo "<strong><a href='".BASEDIR."reports.php' class='side'>".$report_count1." nye anmeldelser!</a></strong>\n</div><br />\n";
			}
		}
		if ($msg_count) {
			echo "<div style='text-align:center;margin-top:15px;'>\n";
			echo "<strong><a href='".BASEDIR."messages.php' class='side'>Der er ".$msg_count.($msg_count == 1 ? ' ny besked' : ' nye beskeder').'</a></strong>';
			echo "</div>\n";
		}
		if (isset($inbox_full))
		{
			if ($inbox_full > 0)
			{
				echo '<strong>Om '.$inbox_full.' beskeder er din indbakke fuld. :(</strong><br />';
			}
			else
			{
				echo '<strong>Din indbakke er fuld. :(</strong><br />';
			}
		}
		closeside();
	}


} else {
	if (!preg_match('/login.php/i',FUSION_SELF)) {
		$action_url = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
		if (isset($_GET['redirect']) && strstr($_GET['redirect'], "/")) {
			$action_url = cleanurl(urldecode($_GET['redirect']));
		}

		openside($locale['global_100']);
		echo "<div style='text-align:center'>\n";
		echo "<form name='loginform' method='post' action='".$action_url."'>\n";
		echo $locale['global_101']."<br />\n<input type='text' name='user_name' class='textbox' style='width:100px' /><br />\n";
		echo $locale['global_102']."<br />\n<input type='password' name='user_pass' class='textbox' style='width:100px' /><br />\n";
		echo "<label><input type='checkbox' name='remember_me' value='y' title='".$locale['global_103']."' style='vertical-align:middle;' /></label>\n";
		echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' /><br />\n";
		echo "</form>\n<br />\n";

		if ($settings['enable_registration']) {
			echo $locale['global_105']."<br /><br />\n";
		}
		echo $locale['global_106']."\n</div>\n";
		closeside();
	}
}
?>
