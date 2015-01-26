<?php
require_once "../maincore.php";
require_once THEMES."templates/header.php";
if (!iMEMBER)
{
	redirect('/index.php');
}
if (isset($_GET['id']) && isnum($_GET['id']))
{
	$query1 = dbquery('SELECT bp_id, bp_author FROM '.DB_PREFIX.'blogposts WHERE bp_id = '.$_GET['id']);
	if (dbrows($query1))
	{
		$row1 = dbarray($query1);
		unset($query1);
	}
	else
	{
		opentable('Sletning af blogindl�g');
		echo 'Sletningen af indl�gget fejlede. Indl�gget kunne ikke findes. <a href="/blog/liste.php?list=newest">G� tilbage til nyeste blogindl�g.</a>';
		closetable();
		require_once THEMES."templates/footer.php";
		die();
	}
		
	if (!iADMIN && $row1['bp_author'] != $userdata['user_id'])
	{
		redirect('/index.php');
	}

	if (dbquery('DELETE FROM '.DB_PREFIX.'blogposts WHERE bp_id = '.$_GET['id']) && dbquery('UPDATE '.DB_USERS.' SET user_blog = user_blog - 1 WHERE user_id = '.$row1['bp_author']))
	{
		opentable('Sletning af blogindl�g');
		echo 'Indl�gget er blevet slettet. <a href="/blog/liste.php?list=newest">G� tilbage til nyeste blogindl�g.</a>';
		closetable();
		require_once THEMES."templates/footer.php";
	}
	else
	{
		opentable('Sletning af blogindl�g');
		echo 'Sletningen af indl�gget fejlede muligvis. Pr�v igen og/eller anmeld problemet. <a href="/blog/liste.php?list=newest">G� tilbage til nyeste blogindl�g.</a>';
		closetable();
		require_once THEMES."templates/footer.php";
	}
}
?>
