<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }
if (!is_array($userdata['user_aliases'])) { $userdata['user_aliases'] = alias1($userdata['user_aliases']); }


if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>Vælg 3 aliaser - Det er tilladt at lade standart-værdien stå</td>\n";
	echo "<td class='tbl'>1: <input type='text' name='user_alias0' value='".(isset($userdata['user_aliases']) ? $userdata['user_aliases'][0] : '')."' /><br />2: <input type='text' name='user_alias1' value='".(isset($userdata['user_aliases']) ? $userdata['user_aliases'][1] : '')."' /><br />3: <input type='text' name='user_alias2' value='".(isset($userdata['user_aliases']) ? $userdata['user_aliases'][2] : '')."' /></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	//Empty
} elseif ($profile_method == "validate_insert") {
	if (!isset($_POST['user_alias0'],$_POST['user_alias1'],$_POST['user_alias2']))
	{
		$this->_setError("user_aliases", 'Et eller flere af aliaserne er tomme!');
	}
	elseif (preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias0']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias1']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias2']) && $_POST['user_alias0'] !== $_POST['user_alias1'] && $_POST['user_alias1'] !== $_POST['user_alias2'] && $_POST['user_alias2'] !== $_POST['user_alias0'])
	{
		$ualias1_result = dbquery('SELECT user_aliases, user_id FROM '.DB_PREFIX.'users WHERE user_aliases REGEXP "^.*,('.$_POST['user_alias0'].'|'.$_POST['user_alias1'].'|'.$_POST['user_alias2'].'),.*$" AND user_id != '.$userdata['user_id']);

		if (dbrows($ualias1_result) > 0)
		{
			$this->_setError("user_aliases", 'Et eller flere af aliaserne er optagede!');
		}
		else
		{
			$db_values .= ', ",'.$_POST['user_alias0'].','.$_POST['user_alias1'].','.$_POST['user_alias2'].',"';
		}
	}
	else
	{
		$this->_setError("user_aliases", 'Et eller flere af aliaserne er ugyldige!');
	}
} elseif ($profile_method == "validate_update") {
	global $userdata;
	if (!isset($_POST['user_alias0'],$_POST['user_alias1'],$_POST['user_alias2']))
	{
		$this->_setError("user_aliases", 'Et eller flere af aliaserne er tomme!');
	}
	elseif (preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias0']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias1']) && preg_check("/^[-0-9A-Z_@\sæøåÆØÅ]{3,30}$/i",$_POST['user_alias2']) && $_POST['user_alias0'] !== $_POST['user_alias1'] && $_POST['user_alias1'] !== $_POST['user_alias2'] && $_POST['user_alias2'] !== $_POST['user_alias0'])
	{
		$ualias1_result = dbquery('SELECT user_aliases, user_id FROM '.DB_PREFIX.'users WHERE user_aliases REGEXP "^.*,('.$_POST['user_alias0'].'|'.$_POST['user_alias1'].'|'.$_POST['user_alias2'].'),.*$" AND user_id != '.$userdata['user_id']);
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