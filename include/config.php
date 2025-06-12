<?php
// Report all errors except E_NOTICE
// error_reporting(E_ALL & ~E_NOTICE);
//
// disable all:
 error_reporting(0);
//
//definir le Fuseau Horaire
//define the Time Zone of your region or country
date_default_timezone_set('Africa/Casablanca');
//
// header lines for information
define("HEADER_CAT","Analog-FM Repeater");
define("HEADER_QTH","QTH:Your_City");
define("HEADER_QRG","QRG:145.250 MHz");
define("HEADER_SYSOP","Sysop:Your_CALL");
define("FMNETWORK_EXTRA","");
// define("EL_NODE_NR","123456"); /*facultatif*/
//
// Path and file name of confguration
// Chemin et nom de fichier de configuration
define("SVXCONFPATH", "/etc/svxlink");
define("SVXCONFIG", "svxlink.conf");
//
// Path and file name of log
// Chemin et nom de fichier du journal
define("SVXLOGPATH", "/var/log");
define("SVXLOGPREFIX", "svxlink");
//
// Orange Pi Zero LTS version requires CPU_TEMP_OFFSET value 30
// to display CPU TEMPERATURE correctly
// La version Orange Pi Zero LTS nécessite une valeur CPU_TEMP_OFFSET de 30
// pour afficher correctement la TEMPÉRATURE DU CPU
define("CPU_TEMP_OFFSET","0");
//
//$svxConfigFile = '/etc/svxlink/svxlink.conf';
$svxConfigFile = SVXCONFPATH."/".SVXCONFIG;
    if (fopen($svxConfigFile,'r'))
       { $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
         $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
         $refApi = $svxconfig['ReflectorLogic']['API'];
         $fmnetwork =$svxconfig['ReflectorLogic']['FMNET'];   }
else { $callsign="N0CALL";
       $fmnetwork="no registered";
}
//
// Define name of your FM Network
define("FMNETWORK", $fmnetwork);
//
// Select only one URL for SVXReflector API to get connected Nodes
// Sélectionnez une seule URL pour l'API SVXReflector pour obtenir des nœuds connectés
//
// définir l'URL de l'API SVXReflector à partir de svxlink.conf
define("URLSVXRAPI", $refApi);
//
// Empty address API do not show connected nodes to svxreflector
//define("URLSVXRAPI", "");
//
// Put url address to your svxreflector wihc offer information of status
//define("URLSVXRAPI", "http://192.168.1.33:9999/status");
//
?>
