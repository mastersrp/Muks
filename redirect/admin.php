<?php
require('../maincore.php');
if (iADMIN)
{
	header('Location: /administration/index.php'.$aidlink);
}
else
{
	header('Location: /news.php');
}
die();
?>