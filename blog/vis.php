<?php
require_once "../maincore.php";
require_once THEMES."templates/header.php";
if (!iMEMBER)
{
	redirect('/index.php');
}

$list = isset($_GET['bp_id']) && isnum($_GET['bp_id']) ? $_GET['bp_id'] : 0;

opentable('Blog');

echo '<a href="/blog/liste.php">Mine blogindlæg</a> :: <a href="/blog/liste.php?list=newest">Nyeste blogindlæg</a> :: <a href="/blog/liste.php?list=blogs">Brugere med blogindlæg</a> :: <a href="/blog/skriv.php">Skriv nyt blogindlæg</a><br />';

if (isnum($list))
{
	$bloglist = dbquery('SELECT bp.*, u.user_avatar, u.user_id, u.user_name, u.user_blog, u.user_status FROM '.DB_PREFIX.'blogposts AS bp INNER JOIN '.DB_PREFIX.'users AS u ON u.user_id = bp.bp_author WHERE bp.bp_id = '.$list);
}

echo '<table style="width: 100%;"  class="tbl-border forum_thread_table">';
if ($blogarr = dbarray($bloglist))
{
	echo '
<tr>
<td style="width: 25%;" class="tbl2 forum-caption">'.showdate('forumdate',$blogarr['bp_timestamp']).'</td>
<td style="width: 75%;" class="tbl2 forum-caption">'.$blogarr['bp_title'].'</td>
</tr>
<tr>
<td style="width: 25%;" class="tbl2 forum-caption">
'.profile_link($blogarr['bp_author'], $blogarr['user_name'], $blogarr['user_status']).'<br />';

if ($blogarr['user_avatar'] && file_exists(IMAGES."avatars/".$blogarr['user_avatar']) && $blogarr['user_status']!=6 && $blogarr['user_status']!=5)
{
	echo "<img src='".IMAGES."avatars/".$blogarr['user_avatar']."' alt='Avatar' /><br /><br />\n";
}

echo 'Antal blogindlæg: '.$blogarr['user_blog'].'<br />
<a href="/blog/liste.php?list='.$blogarr['user_id'].'">Se blog</a>
'.(iADMIN || $blogarr['user_id'] == $userdata['user_id'] ? '<br /><a href="/blog/slet.php?id='.$blogarr['bp_id'].'" onclick="return confirm(\'Er du sikker på, du vil slette dette indlæg?\')">Slet indlæg</a>' : '').'
<br /><a href="/report.php?action=new&amp;bp_id='.$blogarr['bp_id'].'">Anmeld</a>
</td>
<td style="width: 75%;" class="tbl1">'.nl2br(parseubb(phpentities($blogarr['bp_content']))).'</td>
</tr>
<tr>
<td colSpan="2" style="width: 5px;">&nbsp;</td>
</tr>
';
}
else
{
		echo 'Indlægget kunne ikke findes.';
}
echo '</table>';

closetable();

require_once THEMES."templates/footer.php";
?>
