<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: forum_threads_list_panel.php
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

global $lastvisited;

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }



opentable('');

/* Blog */
$result = dbquery(
	'SELECT bp.*, u.user_avatar, u.user_id, u.user_name, u.user_status FROM '.DB_PREFIX.'blogposts AS bp INNER JOIN '.DB_PREFIX.'users AS u ON u.user_id = bp.bp_author ORDER BY bp.bp_timestamp DESC LIMIT 0, 7'
);

if (dbrows($result)) {
	$i = 0;
	echo "<table cellpadding='0' cellspacing='1' style='float: left; width: 49%; margin-right: 1%;' class='tbl-border'>\n<tr>\n";
	echo "<td style='width: 70%; margin: 0px;' class='tbl2'><strong>Nyt i bloggen - skrevet af</strong></td>\n";
	echo "<td class='tbl2' style='text-align:center;white-space:nowrap; margin: 0px; width: 30%;'><strong>Tidspunkt</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n";
		echo "<td style='margin: 0px; font-size: 7pt;' class='".$row_color."'><a href='/blog/liste.php?list=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
		echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap; margin: 0px; font-size: 7pt;'>".showdate("%d-%m-%y %H:%M:%S", $data['bp_timestamp'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";

}

/* Nyeste emner */
$result = dbquery(
	"SELECT tt.thread_id, tt.thread_subject, tt.thread_lastpost,
	tt.thread_poll, tf.forum_id, tf.forum_name, tf.forum_access
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_hidden='0'
	ORDER BY tt.thread_id DESC LIMIT 0, 7"
);

if (dbrows($result)) {
	$i = 0;
	echo "<table  style='float: left; width: 50%;' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td style='width: 70%; margin: 0px;' class='tbl2'><strong>Nyeste emner</strong></td>\n";
	echo "<td class='tbl2' style='text-align:center;white-space:nowrap; margin: 0px; width: 30%;'><strong>Seneste svar</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n";
		echo "<td style='margin: 0px; font-size: 7pt;' class='".$row_color."'><a href='/forum/viewthread.php?thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a></td>\n";
		echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap; margin: 0px; font-size: 7pt;'>".showdate("%d-%m-%y %H:%M:%S", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";

}

echo "<div style='clear: both;'></div>";

/* Tilfældige emner */
$result = dbquery(
	"SELECT tt.thread_id, tt.thread_subject, tt.thread_lastuser, tt.thread_lastpost, tt.thread_lastpost_alias,
	tt.thread_poll, tf.forum_id, tf.forum_name, tf.forum_access, tt.thread_lastpostid, tu.user_id, tu.user_name,
	tu.user_status, tu.user_aliases
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_hidden='0' AND tt.forum_id != 10
	ORDER BY RAND() DESC LIMIT 0, 5"
);

if (dbrows($result)) {
	$i = 0;
	
	echo "<table cellpadding='0' cellspacing='1' style='width: 100%; margin-top: 1%;' class='tbl-border'>\n<tr>\n";
	echo "<td style='width: 70%; margin: 0px;' class='tbl2'><strong>Tilf&aelig;ldige emner</strong></td>\n";
	echo "<td class='tbl2' style='text-align:center;white-space:nowrap; margin: 0px;'><strong>Nyeste svar</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n";
		if ($data['thread_lastpost'] > $lastvisited) {
			$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
			if (iMEMBER && ($data['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
				$boldynew = false;
			} else {
				$boldynew = true;
			}
		} else {
			$boldynew = false;
		}

		echo "<td style='margin: 0px; font-size: 7pt;' class='".$row_color."'>".($boldynew ? '<b>' : '')."<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$data['thread_subject']."'>".trimlink(preg_replace('/([^\s]{20})([^\s]+)/','$1 $2 ', $data['thread_subject']), 70)."</a><br />\n".$data['forum_name'].($boldynew ? '</b>' : '')."</td>\n";
		echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap; margin: 0px; font-size: 7pt;'>".alias2($data['thread_lastpost_alias'],trimlink(alias1($data['user_aliases']), 14),$data['thread_lastuser'], trimlink($data['user_name'], 14), $data['user_status'])."<br />\n".showdate("%d-%m-%y %H:%M:%S", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";

}

closetable();
?>
