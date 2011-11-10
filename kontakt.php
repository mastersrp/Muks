<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: contact.php
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
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."contact.php";

add_to_title($locale['global_200'].$locale['400']);

opentable($locale['400']);

?>
Brug for at kontakte siden? Der f�lger her en liste over hvem der skal kontaktes med hvad<br>
<br>
<b>Lunaba <a href='/messages.php?msg_send=4'>[Privat besked]</a></b> - <small>Formand i foreningen; Ledende Admin; Overhoved for moderatorene</small><br>
- Sp�rgsm�l, klager og rapportering mht. redaktionen generelt.<br>
- Ting mht. moderationen af siden samt arbejdsgruppen for dette. <br>
<br>
<b>Mickelvlm <a href='/messages.php?msg_send=1'>[Privat besked]</a></b> - <small>N�stformand i forening; Ledende Admin; Overhoved for kodning</small><br>
- Sp�rgsm�l, klager og rapportering mht. redaktionen generelt.<br>
- Ting mht. sidens kodning og arbejdsgruppen for denne.<br>
- Rapporteringer af sikkerhedsproblemer p� siden.<br>
<br>
<b>AutoFyld - <a href='/messages.php?msg_send=5'>[Privat besked]</a></b> - <small>Sekret�r i foreningen; Overhoved for Grafik &amp; Design</small><br>
- Ting mht. sidens design, andet grafik og arbejdsgruppen for disse.<br>
- Indmeldelser til foreningen Muks.<br>
<br>
<b>Jantesviker <a href='/messages.php?msg_send=2'>[Privat besked]</a></b> - <small>Kasserer i foreningen; Overhoved for skribenterne</small><br>
- Ting mht. Muks' tekstbaserede indhold samt arbejdsgruppen for dette.<br>
- Henvendelser mht. foreningen Muks' �konomi.<br>
<br>
<b>info (AT) muks (DOT) dk</b> - <small>F�lles email for administratore</small><br>
- Ting som du ikke kan passe ind under de overst�ende.<br>
- Ting der ikke kan sendes via privat besked.<br>
<?php

closetable();
require_once THEMES."templates/footer.php";
?>
