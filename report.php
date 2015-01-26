<?php
require_once "maincore.php";
if (!iMEMBER)
{
	redirect('/index.php');
}

$action = isset($_GET['action']) && preg_match('/^(new|create|done|zap)$/',$_GET['action']) ? $_GET['action'] : 'new';
if ($action == 'new' || $action == 'create')
{
	if (dbrows(dbquery('SELECT report_id, report_uid FROM '.DB_PREFIX.'reports WHERE report_uid = "'.$userdata['user_id'].'" AND report_zap < 1')) > 35)
	{
		require_once THEMES."templates/header.php";
		opentable('Anmeld indhold - Fejl!');
		echo 'Handlingen blev ikke udført - Du har allerede 35 aktive anmeldelser. Prøv igen senere.';
		closetable();
		require_once THEMES."templates/footer.php";
		die();
	}
}

if (isset($_GET['post_id']) && isnum($_GET['post_id']))
{
	$rid = $_GET['post_id'];
	$rtable = 'posts';
	$ridtype = 'post_id';
	$rcontent = 'post_message';
	$rqcontent = 'SELECT p.post_id, p.post_message, p.forum_id, f.forum_id FROM '.DB_PREFIX.'posts AS p INNER JOIN '.DB_PREFIX.'forums AS f ON p.forum_id = f.forum_id WHERE p.post_id= '.$rid.' AND '.groupaccess('f.forum_access');
}
elseif (isset($_GET['user_id']) && isnum($_GET['user_id']))
{
	$rid = $_GET['user_id'];
	$rtable = 'users';
	$ridtype = 'user_id';
	$rcontent = 'user_name';
	$rqcontent = 'SELECT user_id, user_name FROM '.DB_PREFIX.'users WHERE user_id = '.$rid;
}
elseif (isset($_GET['bp_id']) && isnum($_GET['bp_id']))
{
	$rid = $_GET['bp_id'];
	$rtable = 'blogposts';
	$ridtype = 'bp_id';
	$rcontent = 'bp_content';
	$rqcontent = 'SELECT bp_id, bp_content FROM '.DB_PREFIX.'blogposts WHERE bp_id = '.$rid;
}
elseif (isset($_GET['message_id']) && isnum($_GET['message_id']))
{
	$rid = $_GET['message_id'];
	$rtable = 'messages';
	$ridtype = 'message_id';
	$rcontent = 'message_message';
	$rqcontent = 'SELECT message_id, message_message, message_to FROM '.DB_PREFIX.'messages WHERE message_id = '.$rid.' AND message_to='.$userdata['user_id'];
}
elseif (isset($_GET['comment_id'],$_GET['comment_type']) && isnum($_GET['comment_id']) && preg_match('/^(A|N)$/',$_GET['comment_type']))
{
	$rid = $_GET['comment_id'];
	$rtable = 'comments';
	$ridtype = 'comment_id';
	$rcontent = 'comment_message';
	$comment_type = $_GET['comment_type'];
	if ($comment_type === 'N')
	{
		$rqcontent = 'SELECT c.comment_id, c.comment_message, c.comment_item_id, c.comment_type, n.news_id, n.news_visibility FROM '.DB_PREFIX.'comments AS c INNER JOIN '.DB_PREFIX.'news AS n ON n.news_id = c.comment_item_id AND c.comment_type = "N" WHERE c.comment_id= '.$rid.' AND '.groupaccess('n.news_visibility');
	}
	elseif ($comment_type == 'A')
	{
		$rqcontent = 'SELECT c.comment_id, c.comment_message, c.comment_item_id, c.comment_type, a.article_id, a.article_cat, ac.article_cat_id, ac.article_cat_access FROM '.DB_PREFIX.'comments AS c INNER JOIN '.DB_PREFIX.'articles AS a ON a.article_id = c.comment_item_id AND c.comment_type = "A" INNER JOIN '.DB_PREFIX.'article_cats AS ac ON ac.article_cat_id = a.article_cat WHERE c.comment_id= '.$rid.' AND '.groupaccess('ac.article_cat_access');
	}
}
else
{
	if ($action != 'done' && $action != 'zap')
	{
		redirect('/index.php');
	}
}

if ($action == 'new')
{	
	$query1 = dbquery($rqcontent);
	if (dbrows($query1) && $data1 = dbarray($query1))
	{
		require_once THEMES."templates/header.php";
		opentable('Anmeld indhold på Muks');
		if (isset($_GET['err']))
		{
			echo '<b style="color: maroon;">Din anmeldelse er for lang!</b><br>';
		}
		echo 
		'<h3>Du har valgt at anmelde den '.($ridtype == 'user_id' ? 'den følgende bruger' : 'det følgende').':</h3><br />'
		.parseubb($data1[$rcontent])
		.'<br /><br /><br /><h3>Angiv en grund:</h3>'
		.'<form action="report.php?action=create&amp;'.$ridtype.'='.$rid.($ridtype == 'comment_id' ? '&amp;comment_type='.$_GET['comment_type'] : '').'" method="post">'
		.'<textarea name="report_content" id="report_content" cols="50" rows="10"></textarea><br><input type="submit" value="Anmeld" />'
		.'</form>';
		closetable();
		require_once THEMES."templates/footer.php";
	}
	else
	{
		redirect('/index.php');
	}
}
elseif ($action == 'create')
{
	$query1 = dbquery($rqcontent);
	if (isset($_POST['report_content']) && dbrows($query1) && $data1 = dbarray($query1))
	{
		if (strlen($_POST['report_content']) > 400)
		{
			redirect('/report.php?action=new&'.$ridtype.'='.$rid.(isset($comment_type) ? '&comment_type='.$comment_type : '').'&err=1');
		}
		$report_content = mysql_real_escape_string(stripslashes($_POST['report_content']));
		if (dbquery('INSERT INTO '.DB_PREFIX.'reports (report_uid, report_zap, report_zapper, report_content, report_timestamp, report_subject, report_type) VALUES ("'.$userdata['user_id'].'", 0, 0, "'.$report_content.'", "'.time().'", "'.$rid.'", "'.$ridtype.'")'))
		{
			redirect('report.php?action=done');
		}
		else
		{
			require_once THEMES."templates/header.php";
			opentable('Anmeld indhold - Fejl!');
			echo 'Handlingen blev muligvis ikke udført - prøv igen og/eller send anmeld problemet i forumemnet til andmeldelse af fejl.';
			closetable();
			require_once THEMES."templates/footer.php";
		}
	}
	else
	{
		redirect('/index.php');
	}
}
elseif ($action == 'zap' && isset($_POST['report_id']) && isnum($_POST['report_id']))
{
	$query2 = dbquery('SELECT report_id, report_type FROM '.DB_PREFIX.'reports WHERE report_id = "'.$_POST['report_id'].'"');
	if (dbrows($query2))
	{
		$row2 = dbarray($query2);
		unset($query2);
	}
	if (!iADMIN || !isset($row2) || ($row2['report_type'] == 'message_id' && !iSUPERADMIN))
	{
		redirect('/index.php');
	}
	if (dbquery('UPDATE '.DB_PREFIX.'reports SET report_zap = '.time().', report_zapper = '.$userdata['user_id'].' WHERE report_id = '.$_POST['report_id'].' AND report_zap < 1'))
	{
		redirect('report.php?action=done');
	}
	else
	{
		require_once THEMES."templates/header.php";
		opentable('Zapning af anmeldelser - Fejl!');
		echo 'Handlingen blev muligvis ikke udført - prøv igen og/eller anmeld problemet.';
		closetable();
		require_once THEMES."templates/footer.php";
	}
}
elseif ($action == 'done')
{
	require_once THEMES."templates/header.php";
	opentable('Anmeldelse - Din handling blev udført!');
	echo 'Handlingen blev udført. :)';
	closetable();
	require_once THEMES."templates/footer.php";
}
else
{
	redirect('/index.php');
}
?>
