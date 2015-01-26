<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: viewthread_mod_options.php
| Author: Slawomir Nonas (slawekneo)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (!defined("iMOD") || !iMOD) { redirect("index.php"); }

if (isset($_POST['delete_posts']) && isset($_POST['delete_post']) && is_array($_POST['delete_post']) && count($_POST['delete_post'])) {
	$del_posts = ""; $i = 0; $post_count = 0;
	foreach ($_POST['delete_post'] as $del_post_id) {
		if (isnum($del_post_id)) { $del_posts .= ($del_posts ? "," : "").$del_post_id; $i++; }
	}
	if ($del_posts) {
		$result = dbquery("SELECT post_author, post_alias, COUNT(post_id) as num_posts FROM ".DB_POSTS." WHERE post_id IN (".$del_posts.") GROUP BY post_author");
		if (dbrows($result)) {
			while ($pdata = dbarray($result)) {
				if ($pdata['post_alias'] < 0)
				{
					$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts-".$pdata['num_posts']." WHERE user_id='".$pdata['post_author']."'");
					$post_count = $post_count + $pdata['num_posts'];
				}
			}
		}

		$result = dbquery("DELETE FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' AND post_id IN(".$del_posts.")");
	}
	if (!dbcount("(post_id)", DB_POSTS, "thread_id='".$_GET['thread_id']."'")) {
		$result = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLLS." WHERE thread_id='".$_GET['thread_id']."'");
		$thread_count = false;
	} else {
		$result = dbquery("SELECT post_datestamp, post_author, post_id, post_alias FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' ORDER BY post_datestamp DESC LIMIT 1");
		$pdata = dbarray($result);
		$result = dbquery("UPDATE ".DB_THREADS." SET thread_lastpost='".$pdata['post_datestamp']."', thread_lastpostid='".$pdata['post_id']."', thread_postcount=thread_postcount-1, thread_lastuser='".$pdata['post_author']."', thread_lastpost_alias='".$pdata['post_alias']."' WHERE thread_id='".$_GET['thread_id']."'");
		$thread_count = true;
	}
	$result = dbquery("SELECT post_datestamp, post_author, post_alias FROM ".DB_POSTS." WHERE forum_id='".$fdata['forum_id']."' ORDER BY post_datestamp DESC LIMIT 1");
	if (dbrows($result)) {
		$pdata = dbarray($result);
		$forum_lastpost = "forum_lastpost='".$pdata['post_datestamp']."', forum_lastuser='".$pdata['post_author']."', forum_lastpost_alias = ".$pdata['post_alias'];
	} else {
		$forum_lastpost = "forum_lastpost='0', forum_lastuser='0', forum_lastpost_alias=-1";
	}
	$result = dbquery("UPDATE ".DB_FORUMS." SET ".$forum_lastpost.(!$thread_count ? ", forum_threadcount=forum_threadcount-1," : ",")." forum_postcount=forum_postcount-".$post_count." WHERE forum_id = '".$fdata['forum_id']."'");
	if (!$thread_count) { redirect("viewforum.php?forum_id=".$fdata['forum_id']); }
} elseif (isset($_POST['move_posts']) && isset($_POST['delete_post']) && is_array($_POST['delete_post']) && count($_POST['delete_post'])) {
	redirect('index.php');
} elseif (isset($_GET['error']) && isnum($_GET['error'])) {
	if ($_GET['error'] == 1) {
		$message = $locale['error-MP001'];
	} elseif ($_GET['error'] == 2) {
		$message = $locale['error-MP002'];
	} elseif ($_GET['error'] == 3) {
		$message = $locale['error-MP003'];
	} else {
		$message = "";
	}
	if ($message != "") {
		opentable($locale['error-MP000']);
		echo "<div id='close-message'><div class='admin-message'>".$message."<br /><br />\n";
		echo "<a href='".FORUM."viewthread.php?thread_id=".$fdata['thread_id']."&amp;rowstart=".$_GET['rowstart']."'>".$locale['609']."</a><br />";
		echo "</div></div>\n";
		closetable();
		require_once THEMES."templates/footer.php";
		die();
	}
}
?>
