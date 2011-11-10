<?php
//Noter - Mickelvlms tanker for hvad der skal v�re i adminpalenet og p� selve forsiden.
// - Adminpanel
//Tekstfelt: Kommasepereret liste over IDer p� posts, den tilf�ldigt skal v�lge mellem x 2
//Tekstfelt: Kommasepereret liste over IDer p� nyheder, den tilf�ldigt skal v�lge imellem x1
//Tekstfelt: Kommasepereret liste over IDer p� emner, den tilf�ldigt skal v�lge imellem x1
//Tekstboks til tema-link, Tekstboks til intro af tema-post, tekstfelt til billede-link (190px bredde)
//Tekstboks til brugerdefineret tekst
// - Visning
//�verst: Nyhed 1
//Venstre: Nyhed 2, Highlighted indl�g, (Boks med brugerdefineret tekst)
//H�jre: Tema 1, Highlighted debat, Nyeste blogindl�g (hvis logget ind)
//Det HTML/PHP der allerede er, skulle gerne efterligne det forsl�ede design nogenlunde. Det ser ikke helt s� p�nt ud som forslaget, men det ville nok desv�rre pt. v�re ret besv�rgligt med de forskellige temaer i det nuv�rende system. Derfor har jeg brugt PHP-Fusions tabelfunktioner.
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
echo '(Highlighted indl�g)';
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
	opentable('Nyeste blogindl�g');
	echo '(Nyeste blogindl�g)';
	closetable();
}
echo '</td></tr></table>';
require_once THEMES."templates/footer.php";
?>
