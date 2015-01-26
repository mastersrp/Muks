<?php
require_once "maincore.php";
if (!iSUPERADMIN) { redirect('/index.php'); }
$q1 = dbquery('SELECT * FROM muksfusion_postlog WHERE plog_id IN (3755, 3800)');
while ($r1 = dbarray($q1))
{
	$r1['plog_post'] = unserialize(gzuncompress($r1['plog_post']));
	print_r($r1)."\n\n\n";
}
?>
