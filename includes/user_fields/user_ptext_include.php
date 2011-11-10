<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	require_once INCLUDES."bbcode_include.php";
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>Profiltekst</td>\n";
	echo "<td class='tbl'><textarea name='user_ptext' cols='60' rows='10' class='textbox' style='width:295px'>".(isset($user_data['user_ptext']) ? $user_data['user_ptext'] : "")."</textarea><br />\n";
	echo display_bbcodes("300px", "user_ptext", "inputform", "smiley|b|i|u||center|small|url|mail|img|color")."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_ptext']) {
		echo "<tr>\n";
		echo "<td align='left' colSpan='2' class='tbl1'>";
		echo nl2br(parseubb(parsesmileys($user_data['user_ptext'])))."\n";
		echo "</td>\n</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_ptext";
	$db_values .= ", '".(isset($_POST['user_ptext']) ? stripinput(trim($_POST['user_ptext'])) : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_ptext='".(isset($_POST['user_ptext']) ? stripinput(trim($_POST['user_ptext'])) : "")."'";
}
?>