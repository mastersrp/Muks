<?php
require_once "maincore.php";
if (!iADMIN)
{
	redirect('/index.php');
}
require_once THEMES."templates/header.php";
$start1 = isset($_GET['start1']) && isnum($_GET['start1']) ? $_GET['start1'] : 0;
$start2 = isset($_GET['start2']) && isnum($_GET['start2']) ? $_GET['start2'] : 0;

opentable('Anmeldelser af misbrug');
$query1 = dbquery('SELECT * FROM '.DB_PREFIX.'reports WHERE report_zap < 1'.(!iSUPERADMIN ? ' AND report_type != "message_id"' : '').' ORDER BY report_id DESC LIMIT '.$start1.', 20');
echo '<table style="width: 99%;"><tr><td style="border: 1px solid black; margin: 1px; padding: 1px;">Type</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Tidspunkt</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Snitch</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Begrundelse</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Link</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Zap</td></tr>';
while ($row1 = dbarray($query1))
{
	$row1['report_content'] = preg_replace('/\[(\/){0,1}img\]/','[$1url]',phpentities($row1['report_content']));
	$report_linky = 'Væk';
	if ($row1['report_type'] == 'post_id')
	{
		$query3 = dbquery('SELECT post_id, thread_id FROM '.DB_POSTS.' WHERE post_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/forum/viewthread.php?thread_id='.$row3['thread_id'].'&amp;post_id='.$row3['post_id'].'#post_'.$row3['post_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'comment_id')
	{
		$query3 = dbquery('SELECT comment_id, comment_item_id, comment_type FROM '.DB_PREFIX.'comments WHERE comment_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			if ($row3['comment_type'] == 'N')
			{
				$report_linky = '<a href="/news.php?readmore='.$row3['comment_item_id'].'#c'.$row3['comment_id'].'">Link</a>';
			}
			elseif ($row3['comment_type'] == 'A')
			{
				$report_linky = '<a href="/articles.php?article_id='.$row3['comment_item_id'].'#c'.$row3['comment_id'].'">Link</a>';
			}
		}
	}
	elseif ($row1['report_type'] == 'user_id')
	{
		$query3 = dbquery('SELECT user_id FROM '.DB_USERS.' WHERE user_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/profile.php?lookup='.$row3['user_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'bp_id')
	{
		$query3 = dbquery('SELECT bp_id FROM '.DB_PREFIX.'blogposts WHERE bp_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/blog/vis.php?bp_id='.$row3['bp_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'message_id')
	{
		$report_linky = 'Næ';
	}
	echo '<tr><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.$row1['report_type'].'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.showdate('forumdate',$row1['report_timestamp']).'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;"><a href="/profile.php?lookup='.$row1['report_uid'].'">'.$row1['report_uid'].'</a></td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.parseubb($row1['report_content']).'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.$report_linky.'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;"><form method="post" action="/report.php?action=zap"><input type="hidden" name="report_id" value="'.$row1['report_id'].'"><input type="submit" value="Zap" /></form></td></tr>';
}
echo '</table>';
echo makepagenav($_GET['start1'],20,dbrows(dbquery('SELECT report_zap FROM '.DB_PREFIX.'reports WHERE report_zap < 1')),3,'/reports.php?start2='.$start2.'&amp;','start1');
closetable();
opentable('Zappede');
$query1 = dbquery('SELECT * FROM '.DB_PREFIX.'reports WHERE report_zap > 0'.(!iSUPERADMIN ? ' AND report_type != "message_id"' : '').' ORDER BY report_id DESC LIMIT '.$start2.', 20');
echo '<table style="width: 99%;"><tr><td style="border: 1px solid black; margin: 1px; padding: 1px;">Type</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Tid</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Snitch</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Grund</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Zapper</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Zapped</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">Link</td></tr>';
while ($row1 = dbarray($query1))
{
	$row1['report_content'] = preg_replace('/\[(\/){0,1}img\]/','[$1url]',phpentities($row1['report_content']));
	$report_linky = 'Væk';
	if ($row1['report_type'] == 'post_id')
	{
		$query3 = dbquery('SELECT post_id, thread_id FROM '.DB_POSTS.' WHERE post_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/forum/viewthread.php?thread_id='.$row3['thread_id'].'&amp;post_id='.$row3['post_id'].'#post_'.$row3['post_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'comment_id')
	{
		$query3 = dbquery('SELECT comment_id, comment_item_id, comment_type FROM '.DB_COMMENTS.' WHERE comment_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			if ($row3['comment_type'] == 'N')
			{
				$report_linky = '<a href="/news.php?readmore='.$row3['comment_item_id'].'#c'.$row3['comment_id'].'">Link</a>';
			}
			elseif ($row3['comment_type'] == 'A')
			{
				$report_linky = '<a href="/articles.php?article_id='.$row3['comment_item_id'].'#c'.$row3['comment_id'].'">Link</a>';
			}
		}
	}
	elseif ($row1['report_type'] == 'user_id')
	{
		$query3 = dbquery('SELECT user_id FROM '.DB_USERS.' WHERE user_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/profile.php?lookup='.$row3['user_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'bp_id')
	{
		$query3 = dbquery('SELECT bp_id FROM '.DB_PREFIX.'blogposts WHERE bp_id = '.$row1['report_subject']);
		if ($row3 = dbarray($query3))
		{
			$report_linky = '<a href="/blog/vis.php?bp_id='.$row3['bp_id'].'">Link</a>';
		}
	}
	elseif ($row1['report_type'] == 'message_id')
	{
		$report_linky = 'Næ';
	}
	echo '<tr><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.$row1['report_type'].'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.showdate('forumdate',$row1['report_timestamp']).'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;"><a href="/profile.php?lookup='.$row1['report_uid'].'">'.$row1['report_uid'].'</a></td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.parseubb($row1['report_content']).'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;"><a href="/profile.php?lookup='.$row1['report_zapper'].'">'.$row1['report_zapper'].'</a></td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.showdate('forumdate',$row1['report_zap']).'</td><td style="border: 1px solid black; margin: 1px; padding: 1px;">'.$report_linky.'</td></tr>';
}
echo '</table>';
echo makepagenav($_GET['start2'],20,dbrows(dbquery('SELECT report_zap FROM '.DB_PREFIX.'reports WHERE report_zap > 0')),3,'/reports.php?start1='.$start1.'&amp;','start2');
closetable();
require_once THEMES."templates/footer.php";
?>
