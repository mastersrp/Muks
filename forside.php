<?php
//Noter - Mickelvlms tanker for hvad der skal være i adminpalenet og på selve forsiden.
// - Adminpanel
//Tekstfelt: Kommasepereret liste over IDer på posts, den tilfældigt skal vælge mellem x 2
//Tekstfelt: Kommasepereret liste over IDer på nyheder, den tilfældigt skal vælge imellem x1
//Tekstfelt: Kommasepereret liste over IDer på emner, den tilfældigt skal vælge imellem x1
//Tekstboks til tema-link, Tekstboks til intro af tema-post, tekstfelt til billede-link (190px bredde)
//Tekstboks til brugerdefineret tekst
// - Visning
//Øverst: Nyhed 1
//Venstre: Nyhed 2, Highlighted indlæg, (Boks med brugerdefineret tekst)
//Højre: Tema 1, Highlighted debat, Nyeste blogindlæg (hvis logget ind)
//Det HTML/PHP der allerede er, skulle gerne efterligne det forslåede design nogenlunde. Det ser ikke helt så pænt ud som forslaget, men det ville nok desværre pt. være ret besværgligt med de forskellige temaer i det nuværende system. Derfor har jeg brugt PHP-Fusions tabelfunktioner.
require_once "maincore.php";
require_once THEMES."templates/header.php";

opentable('(Titel - Dato)');
echo '(Nyhed 1)';
closetable();
echo '<table style="width: 100%;"><tr><td style="vertical-align: top;">';
opentable('Nyhed 2');
echo '(Nyhed 2)';
closetable();

opentable('Brugercitat');
echo '(Highlighted indlæg)';
closetable();
if (1 == 0)
{
	opentable('');
	echo '(Brugerdefineret tekst)';
	closetable();
}
echo '<td style="width: 190px; vertical-align: top;">
<table style="width: 100%;">
<tr><td>(Tema-billede)</td></tr>
<tr><td>(Tema 1 intro)</td></tr>
</table><br />';
opentable('Debat');
echo '(highlighted debat)';
closetable();
if (iMEMBER)
{
	opentable('Nyeste blogindlæg');
	echo '(Nyeste blogindlæg)';
	closetable();
}
echo '</td></tr></table>';
require_once THEMES."templates/footer.php";
?>
