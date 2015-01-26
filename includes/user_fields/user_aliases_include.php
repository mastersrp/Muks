<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }
global $userdata;
if (!isset($GLOBALS['user_id']))
{
	$alias_uid = $userdata['user_id'];
}
else
{
	$alias_uid = $GLOBALS['user_id'];
}
$alias_foo1 = dbarray(dbquery('SELECT user_aliases FROM '.DB_PREFIX.'users WHERE user_id = '.$alias_uid));
$user_data['user_aliases'] = alias1($alias_foo1['user_aliases']);
unset($alias_foo1);

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>Vælg 3 aliaser - Det er tilladt at lade standart-værdien stå</td>\n";
	echo "<td class='tbl'>1: <input type='text' name='user_alias0' value='".(isset($user_data['user_aliases']) ? $user_data['user_aliases'][0] : '')."' /><br />2: <input type='text' name='user_alias1' value='".(isset($user_data['user_aliases']) ? $user_data['user_aliases'][1] : '')."' /><br />3: <input type='text' name='user_alias2' value='".(isset($user_data['user_aliases']) ? $user_data['user_aliases'][2] : '')."' /></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	//Empty
} elseif ($profile_method == "validate_insert") {
	//Empty
} elseif ($profile_method == "validate_update") {
	global $user_data;
	if (!isset($_POST['user_alias0'],$_POST['user_alias1'],$_POST['user_alias2']))
	{
		$this->_setError("user_aliases", 'Et eller flere af aliaserne er tomme!');
	}
	elseif (preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias0']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias1']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias2']) && $_POST['user_alias0'] !== $_POST['user_alias1'] && $_POST['user_alias1'] !== $_POST['user_alias2'] && $_POST['user_alias2'] !== $_POST['user_alias0'])
	{
		$ualias1_result = dbquery('SELECT user_aliases, user_id FROM '.DB_PREFIX.'users WHERE user_aliases REGEXP "^.*,('.$_POST['user_alias0'].'|'.$_POST['user_alias1'].'|'.$_POST['user_alias2'].'),.*$" AND user_id != '.$alias_uid);
		if (dbrows($ualias1_result) > 0)
		{
			$this->_setError("user_aliases", 'Et eller flere af aliaserne er optagede!');
		}
		else
		{
			$db_values .= ', user_aliases=",'.$_POST['user_alias0'].','.$_POST['user_alias1'].','.$_POST['user_alias2'].',"';
		}
	}
	else
	{
		$this->_setError("user_aliases", 'Et eller flere af aliaserne er ugyldige!');
	}
}
?>
