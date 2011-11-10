<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: articles.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/header.php";
if (!iMEMBER)
{
	redirect('/index.php');
}

$list = isset($_GET['list']) ? ($_GET['list'] === 'blogs' ? 'blogs' : (isnum($_GET['list']) ? $_GET['list'] : 'newest')) : $userdata['user_id'];
$rowstart = isnum($_GET['rowstart']) ? $_GET['rowstart'] : 0;
opentable('Blog');

echo '<a href="/blog/liste.php">Mine blogindlæg</a> :: <a href="/blog/liste.php?list=newest">Nyeste blogindlæg</a> :: <a href="/blog/liste.php?list=blogs">Brugere med blogindlæg</a> :: <a href="/blog/skriv.php">Skriv nyt blogindlæg</a><br />';

if (isnum($list))
{
	$bloglist = dbquery('SELECT bp.*, u.user_avatar, u.user_id, u.user_name, u.user_blog, u.user_status FROM '.DB_PREFIX.'blogposts AS bp INNER JOIN '.DB_PREFIX.'users AS u ON u.user_id = bp.bp_author WHERE bp.bp_author = '.$list.' ORDER BY bp.bp_timestamp DESC LIMIT '.$rowstart.', 20');
	$bloglist2 = dbrows(dbquery('SELECT bp_id FROM '.DB_PREFIX.'blogposts WHERE bp_author = '.$list));
}
elseif ($list === 'newest')
{
	$bloglist = dbquery('SELECT bp.*, u.user_avatar, u.user_id, u.user_name, u.user_blog, u.user_status FROM '.DB_PREFIX.'blogposts AS bp INNER JOIN '.DB_PREFIX.'users AS u ON u.user_id = bp.bp_author ORDER BY bp.bp_timestamp DESC LIMIT '.$rowstart.', 20');
	$bloglist2 = dbrows(dbquery('SELECT bp_id FROM '.DB_PREFIX.'blogposts'));
}
elseif ($list === 'blogs')
{
	$bloglist = dbquery('SELECT user_name, user_id, user_blog FROM '.DB_PREFIX.'users WHERE user_blog > 0 ORDER BY user_id ASC LIMIT '.$rowstart.', 20');
	$bloglist2 = dbrows(dbquery('SELECT user_name, user_id, user_blog FROM '.DB_PREFIX.'users WHERE user_blog > 0'));
	while ($blogarr = dbarray($bloglist))
	{
		echo '<a href="/profile.php?lookup='.$blogarr['user_id'].'">'.$blogarr['user_name'].'</a> - <a href="/blog/liste.php?list='.$blogarr['user_id'].'">Se blog</a> - Antal indlæg: '.$blogarr['user_blog']."<br />\n";
	}
}

if ($list !== 'blogs')
{
	echo '<table style="width: 100%;"  class="tbl-border forum_thread_table">';
	while ($blogarr = dbarray($bloglist))
	{
		echo '
<tr>
<td style="width: 20%;" class="tbl2 forum-caption">'.showdate('forumdate',$blogarr['bp_timestamp']).'</td>
<td style="width: 80%;" class="tbl2 forum-caption">'.$blogarr['bp_title'].'</td>
</tr>
<tr>
<td style="width: 20%;" class="tbl2 forum-caption">
'.profile_link($blogarr['bp_author'], $blogarr['user_name'], $blogarr['user_status']).'<br />
'.($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar']) && $data['user_status']!=6 && $data['user_status']!=5 ? "<img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$locale['567']."' /><br /><br />\n" : '').'
Antal blogindlæg: '.$blogarr['user_blog'].'<br />
<a href="/blog/liste.php?list='.$blogarr['user_id'].'">Se blog</a>
</td>
<td style="width: 80%;" class="tbl1">'.nl2br(parseubb(phpentities($blogarr['bp_content']))).'</td>
</tr>
<tr>
<td colSpan="2" style="width: 5px;">&nbsp;</td>
</tr>
';
	}
	echo '</table>';
}

echo makepagenav($_GET['rowstart'],20,$bloglist2,3,FUSION_SELF."?list=".$list."&amp;")."\n";

closetable();

require_once THEMES."templates/footer.php";
?>
