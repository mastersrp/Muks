<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_irc_include.php
| Author: Digitanium
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

// Display user field input
if ($profile_method == "input") {
	$user_irc = isset($user_data['user_irc']) ? stripinput($user_data['user_irc']) : "";
	if ($this->isError()) { $user_irc = isset($_POST['user_irc']) ? stripinput($_POST['user_irc']) : $user_irc; }

	echo "<tr>\n";
	echo "<td class='tbl".$this->getErrorClass("user_irc")."'><label for='user_irc'>Chat brugernavn</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_irc")."'>";
	echo "<input type='text' id='user_irc' name='user_irc' value='".$user_irc."' maxlength='50' class='textbox' style='width:200px;' />";
	echo "</td>\n</tr>\n";
	
// Display in profile
} elseif ($profile_method == "display") {
	if ($user_data['user_irc']) {
		echo "<tr>\n";
		echo "<td class='tbl1'>Chat brugernavn</td>\n";
		echo "<td align='right' class='tbl1'>".$user_data['user_irc']."</td>\n";
		echo "</tr>\n";
	}

// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_irc'])) {
		// Set update or insert user data
		$this->_setDBValue("user_irc", stripinput(trim($_POST['user_irc'])));
	}
}
?>