<?php
require_once "../maincore.php";
if (!iMEMBER)
{
	redirect('/index.php');
}
if (dbrows(dbquery('SELECT bp_timestamp, bp_author FROM '.DB_PREFIX.'blogposts WHERE bp_author = "'.$userdata['user_id'].'" AND bp_timestamp > '.(time()-3600))) >= 5)
{
			require_once THEMES."templates/header.php";
			opentable('Skriv blogindlæg - Fejl!');
			echo 'Handlingen blev ikke udført - 6 blogindlæg i løbet af en time er lige en tand for meget ;) Prøv igen senere.';
			closetable();
			require_once THEMES."templates/footer.php";
			die();
}

require_once THEMES."templates/header.php";
require_once INCLUDES."bbcode_include.php";

if (isset($_POST['post_content'],$_POST['post_title']))
{
		$postcontent = mysql_real_escape_string(stripslashes($_POST['post_content']));
		$posttitle = mysql_real_escape_string(stripslashes($_POST['post_title']));
		$skriv_q1 = dbquery('INSERT INTO '.DB_PREFIX.'blogposts (bp_content, bp_author, bp_timestamp, bp_title) VALUES ("'.$postcontent.'", '.$userdata['user_id'].', '.time().', "'.$posttitle.'")');
		$skriv_q2 = dbquery('UPDATE '.DB_PREFIX.'users SET user_blog = user_blog + 1 WHERE user_id = '.$userdata['user_id']);
		if ($skriv_q1 && $skriv_q2) { redirect('/blog/skriv.php?done=y'); }
		else { redirect('/blog/skriv.php?done=n'); }
}

opentable('Skriv blogindlæg');
echo '<a href="/blog/liste.php">Mine blogindlæg</a> :: <a href="/blog/liste.php?list=newest">Nyeste blogindlæg</a> :: <a href="/blog/liste.php?list=blogs">Brugere med blogindlæg</a> :: <a href="/blog/skriv.php">Skriv nyt blogindlæg</a><br />';
if (isset($_GET['done']))
{
	echo ($_GET['done'] === 'y' ? 'Indlægget blev indsendt' : 'Fejl! Indlægget blev muligvis ikke indsendt!').'<br />';
}
?>
<form action="skriv.php" method="post" name="inputform">
<b>Titel:</b><input type="text" name="post_title" id="post_title" /><br />
<b>Indhold:</b><br /><textarea name="post_content" id="post_content" style="width: 90%;" cols="1" rows="15"></textarea><br />
<input type="submit" value="Indsend" />
</form><br />
<?php
echo display_bbcodes("99%", "post_content");
closetable();
require_once THEMES."templates/footer.php";
?>
