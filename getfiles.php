<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css">
a, a:link, a:visited, a:hover, a:active
{
	color: darkgreen;
}
</style>
</head>
<body>
<?php
function isempty($v) { return preg_match("/^( |\t|\n)*$/",$v); }

$file = isset($_GET['file']) ? $_GET['file'] : '';
if (preg_match('/(\.\.|\/|~)/',$file) || substr_count($file,'config.php') > 0) { die('Ugyldigt filnavn!'); }
$path = isset($_GET['path']) ? $_GET['path'] : '';
$path = trim($path,'/');
if (preg_match('/(\.|~)/',$path)) { die('Ugyldig sti!'); }
$path2 = isempty($path) ? '.' : $path;

if (!isempty($file) ? file_exists($path2.'/'.$file) : false)
{
	echo '<b>'.htmlspecialchars($path.'/'.$file).'</b><br>';
	echo '<textarea rows="5" cols="50">';
	echo htmlspecialchars(file_get_contents($path2.'/'.$file),ENT_QUOTES);
	echo '</textarea>';
}
else
{
	echo '<h3>'.htmlspecialchars(isempty($path) ? '/ - Root' : $path).'</h3><br>';

	if ($handle = opendir(isempty($path) ? '.' : $path))
	{
		while (false !== ($file2 = readdir($handle)))
		{
			if ($file2 != "." && $file2 != ".." && $file2 != 'config.php')
			{
				if (substr_count($file2,'.') > 0)
				{
					echo '<a href="getfiles.php?path='.htmlspecialchars($path).'&amp;file='.htmlspecialchars($file2).'">'.htmlspecialchars($file2)."</a><br>\n";
				}
				else
				{
					echo '<a href="getfiles.php?path='.htmlspecialchars($path).'/'.htmlspecialchars($file2).'"><b>'.htmlspecialchars($file2)."</b></a><br>\n";
				}
			}
		}
		closedir($handle);
	}
}
?>
</body>
</html>
