<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: forum_threads_list_panel.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

global $lastvisited;

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }



opentable($locale['global_040']);

/* Ikke chat */

$timeframe = 0;

$result = dbquery(
	"SELECT tt.thread_id, tt.thread_subject, tt.thread_lastuser, tt.thread_lastpost, tt.thread_lastpost_alias,
	tt.thread_poll, tf.forum_id, tf.forum_name, tf.forum_access, tt.thread_lastpostid, tu.user_id, tu.user_name,
	tu.user_status, tu.user_aliases
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_lastpost >= ".$timeframe." AND tt.thread_hidden='0' AND tt.forum_id != 10
	ORDER BY tt.thread_lastpost DESC LIMIT 0, 15"
);

if (dbrows($result)) {
	$i = 0;
	echo "<table cellpadding='0' cellspacing='1' style='width: 50%; float: left;' class='tbl-border'>\n<tr>\n";
	echo "<td style='width: 70%; margin: 0px;' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
	echo "<td class='tbl2' style='text-align:center;white-space:nowrap; margin: 0px;'><strong>Nyeste</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n";
		if ($data['thread_lastpost'] > $lastvisited) {
			$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
			if (iMEMBER && ($data['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
				$boldynew = false;
			} else {
				$boldynew = true;
			}
		} else {
			$boldynew = false;
		}
		if ($data['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span> ";
		} else {
			$thread_poll = "";
		}

		echo "<td style=' width: 90px; margin: 0px; font-size: 7pt;' class='".$row_color."'>".($boldynew ? '<b>' : '').$thread_poll."<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$data['thread_subject']."'>".trimlink(preg_replace('/([^\s]{20})([^\s]+)/','$1 $2 ', $data['thread_subject']), 70)."</a><br />\n".$data['forum_name'].($boldynew ? '</b>' : '')."</td>\n";
		echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap; margin: 0px; font-size: 7pt;'>".alias2($data['thread_lastpost_alias'],trimlink(alias1($data['user_aliases']), 14),$data['thread_lastuser'], trimlink($data['user_name'], 14), $data['user_status'])."<br />\n".showdate("%d-%m-%y %H:%M:%S", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";

}

/* Chat */

$timeframe = 0;

$result = dbquery(
	"SELECT tt.thread_id, tt.thread_subject, tt.thread_lastuser, tt.thread_lastpost, tt.thread_lastpost_alias,
	tt.thread_poll, tf.forum_id, tf.forum_name, tf.forum_access, tt.thread_lastpostid, tu.user_id, tu.user_name,
	tu.user_status, tu.user_aliases
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_lastpost >= ".$timeframe." AND tt.thread_hidden='0' AND tt.forum_id = 10
	ORDER BY tt.thread_lastpost DESC LIMIT 0, 15"
);

if (dbrows($result)) {
	$i = 0;
	echo "<table cellpadding='0' cellspacing='1' style='width: 50%; float: left;' class='tbl-border'>\n<tr>\n";
	echo "<td style='width: 70%; margin: 0px;' class='tbl2'><strong>Chat emne</strong></td>\n";
	echo "<td class='tbl2' style='text-align:center;white-space:nowrap; margin: 0px;'><strong>Nyeste</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n";
		if ($data['thread_lastpost'] > $lastvisited) {
			$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
			if (iMEMBER && ($data['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
				$boldynew = false;
			} else {
				$boldynew = true;
			}
		} else {
			$boldynew = false;
		}
		if ($data['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span> ";
		} else {
			$thread_poll = "";
		}

		echo "<td style=' width: 90px; margin: 0px; font-size: 7pt;' class='".$row_color."'>".($boldynew ? '<b>' : '').$thread_poll."<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$data['thread_subject']."'>".trimlink(preg_replace('/([^\s]{20})([^\s]+)/','$1 $2 ', $data['thread_subject']), 70)."</a><br />\n".$data['forum_name'].($boldynew ? '</b>' : '')."</td>\n";
		echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap; margin: 0px; font-size: 7pt;'>".alias2($data['thread_lastpost_alias'],trimlink(alias1($data['user_aliases']), 14),$data['thread_lastuser'], trimlink($data['user_name'], 14), $data['user_status'])."<br />\n".showdate("%d-%m-%y %H:%M:%S", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";

}

echo "<div style='clear: both;'></div>";


if (iMEMBER) {
	echo "<div class='tbl1' style='text-align:center'><a href='".INFUSIONS."forum_threads_list_panel/my_threads.php'>".$locale['global_041']."</a> ::\n";
	echo "<a href='".INFUSIONS."forum_threads_list_panel/my_posts.php'>".$locale['global_042']."</a> ::\n";
	echo "<a href='".INFUSIONS."forum_threads_list_panel/new_posts.php'>".$locale['global_043']."</a>";
	if($settings['thread_notify']) {
		echo " ::\n<a href='".INFUSIONS."forum_threads_list_panel/my_tracked_threads.php'>".$locale['global_056']."</a>";
	}
	echo "</div>\n";
}
closetable();
?>
