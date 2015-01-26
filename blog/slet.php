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
		opentable('Sletning af blogindlæg');
		echo 'Sletningen af indlægget fejlede. Indlægget kunne ikke findes. <a href="/blog/liste.php?list=newest">Gå tilbage til nyeste blogindlæg.</a>';
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
		opentable('Sletning af blogindlæg');
		echo 'Indlægget er blevet slettet. <a href="/blog/liste.php?list=newest">Gå tilbage til nyeste blogindlæg.</a>';
		closetable();
		require_once THEMES."templates/footer.php";
	}
	else
	{
		opentable('Sletning af blogindlæg');
		echo 'Sletningen af indlægget fejlede muligvis. Prøv igen og/eller anmeld problemet. <a href="/blog/liste.php?list=newest">Gå tilbage til nyeste blogindlæg.</a>';
		closetable();
		require_once THEMES."templates/footer.php";
	}
}
?>
