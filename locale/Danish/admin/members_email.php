<?php
$locale['email_create_subject'] = "Brugerkonto oprettet ";
$locale['email_create_message'] = "Hej [USER_NAME],\n
Din brugerkonto p� ".$settings['sitename']." er blevet oprettet.\n
Du kan nu logge p� med f�lgende oplysninger:\n
Brugernavn: [USER_NAME]\n
Kodeord: [PASSWORD]\n\n
Venlig hilsen\n
".$settings['siteusername'];

$locale['email_activate_subject'] = "Brugerkonto aktiveret ";
$locale['email_activate_message'] = "Hej [USER_NAME],\n
Din konto p� ".$settings['sitename']." er blevet aktiveret.\n
Du kan nu logge p� med det brugernavn og det kodeord, du har valgt.\n\n
Venlig hilsen\n
".$settings['siteusername'];

$locale['email_deactivate_subject'] = "Behov for genaktivering p� ".$settings['sitename'];
$locale['email_deactivate_message'] = "Hej [USER_NAME],\n
Der er nu mere end ".$settings['deactivation_period']." dag(e) siden du sidst var logget ind p� ".$settings['sitename'].". Din brugerkonto er noteret som inaktiv, men alle dine kontooplysninger og alt indhold er bevaret.\n
For at genaktivere din brugerkonto skal du ganske enkelt klikke p� f�lgende link:\n
".$settings['siteurl']."reactivate.php?user_id=[USER_ID]&code=[CODE]\n\n
Venlig hilsen\n
".$settings['siteusername'];

$locale['email_ban_subject'] = "Din brugerkonto p� ".$settings['sitename']." er blevet udelukket";
$locale['email_ban_message'] = "Hej [USER_NAME],\n
Din brugerkonto p� ".$settings['sitename']." er blevet udelukket af ".$userdata['user_name']." af f�lgende �rsag:\n
[REASON].\n
Hvis du �nsker at f� mere at vide om udelukkelsen, skal du kontakte sideadministratoren via ".$settings['siteemail'].".\n
".$settings['siteusername'];

$locale['email_secban_subject'] = "Din konto p� ".$settings['sitename']." er blevet udelukket";
$locale['email_secban_message'] = "Hej [USER_NAME],\n
Din konto p� ".$settings['sitename']." er blevet udelukket af ".$userdata['user_name']." p� grund af bestemte aktiviteter der tilskrives dig eller har forbindelse til din konto, og som udg�r en sikkerhedsrisiko.\n
Hvis du vil vide mere om denne udelukkelse, s� v�r s� venlig at kontakte sidens administrator p� ".$settings['siteemail'].".\n
".$settings['siteusername'];

$locale['email_suspend_subject'] = "Din konto p� ".$settings['sitename']." er blevet suspenderet";
$locale['email_suspend_message'] = "Hej [USER_NAME]\n
Din brugerkonto p� ".$settings['sitename']." er blevet suspenderet af ".$userdata['user_name']." frem til [DATE] (site time) af f�lgende �rsag:\n
[REASON].\n
Hvis du vil vide mere om denne suspension, s� v�r s� venlig at kontakte os via denne email: ".$settings['siteemail'].".\n
".$settings['siteusername'];
?>