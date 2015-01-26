<?php
require('../maincore.php');
if (iMEMBER)
{
	header('Location: /profile.php?lookup='.$userdata['user_id']);
}
else
{
	header('Location: /news.php');
}
die();
?>