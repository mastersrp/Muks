<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>R�kkef�lge p� forumindl�g</td>\n";
	echo "<td class='tbl'><select name='user_forumorder'><option value='1'>�ldste �verst</option><option value='2'>Nyeste �verst</option></select></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	//Empty
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_forumorder";
	$db_values .= ", '".(isset($_POST['user_forumorder']) ? stripinput(trim($_POST['user_forumorder'])) : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_forumorder='".(isset($_POST['user_forumorder']) ? ($_POST['user_forumorder'] == 1 ? 'ASC' : 'DESC') : '')."'";
}
?>