<?php

// infosvx_V2, Crée par CN8VX Youness, pour l'adapter avec Debian 12, Raspbian (bookworm)

// Chemin vers le fichier de configuration
//$configFile = '/etc/svxlink/svxlink.conf';
$configFile = SVXCONFPATH."/".SVXCONFIG;

// Lire le fichier de configuration
$config = [];
$currentSection = '';

if (file_exists($configFile)) {
    $lines = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Ignorer les commentaires
        if (strpos($line, ';') === 0) {
            continue;
        }

        // Détecter les sections
        if (strpos($line, '[') === 0) {
            $currentSection = trim($line, '[]');
            $config[$currentSection] = [];
        } elseif ($currentSection && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $config[$currentSection][trim($key)] = trim($value, ' "');  // Retire les guillemets
        }
    }
} else {
    die("$configFile not exist.");
}

// Initialisation des variables
$CALLSIGN = '';
$LOGICS = '';
$LOGICSR = '';  // Contiendra ReflectorLogic s'il est actif
$LOGICSC = '';  // Pour indiquer si ReflectorLogic est actif
$callsignR = '';  // ReflectorLogic CALLSIGN

// Vérifier la valeur de LOGICS dans la section GLOBAL
if (isset($config['GLOBAL']['LOGICS'])) {
    $logics = explode(',', $config['GLOBAL']['LOGICS']);
    $activeLogic = '';

    foreach ($logics as $logic) {
        $logic = trim($logic);

        // Vérifier SimplexLogic
        if ($logic === 'SimplexLogic' && isset($config['SimplexLogic']['CALLSIGN'])) {
            $activeLogic = 'SimplexLogic';
            $CALLSIGN = $config['SimplexLogic']['CALLSIGN'];
            $LOGICS .= 'SimplexLogic, ';
        }

        // Vérifier RepeaterLogic
        elseif ($logic === 'RepeaterLogic' && isset($config['RepeaterLogic']['CALLSIGN'])) {
            $activeLogic = 'RepeaterLogic';
            $CALLSIGN = $config['RepeaterLogic']['CALLSIGN'];
            $LOGICS .= 'RepeaterLogic, ';
        }

        // Vérifier ReflectorLogic pour obtenir son CALLSIGN
        elseif ($logic === 'ReflectorLogic') {
            $LOGICSR = 'ReflectorLogic';
            if (isset($config['ReflectorLogic']['CALLSIGN'])) {
                $callsignR = $config['ReflectorLogic']['CALLSIGN'];
                $LOGICSC = 'Connected';  // ReflectorLogic est actif
            } else {
                $LOGICSC = 'Not Connected';  // ReflectorLogic n'est pas actif
            }
        }
    }

    // Retirer la dernière virgule et espace de $LOGICS
    $LOGICS = rtrim($LOGICS, ', ');

    // Si aucune logique active (SimplexLogic ou RepeaterLogic) n'a été trouvée
    if (empty($CALLSIGN)) {
        $CALLSIGN = 'CALLSIGN';  // Valeur par défaut
    }

    if (empty($callsignR)) {
        $callsignR = 'No Connected';  // Valeur par défaut
    }

    if (empty($LOGICSC)) {
        $LOGICSC = 'Not Connected';  // Valeur par défaut si ReflectorLogic n'est pas trouvé
    }

} else {
    $CALLSIGN = 'CALLSIGN';  // Valeur par défaut
    $LOGICS = 'LOGICS';      // Valeur par défaut
    $LOGICSC = 'Not Connected';  // Valeur par défaut
}

// Sortir simplement les valeurs des variables
//echo "$CALLSIGN\n";   //Contien le CALLSIGN du SimplexLogic ou RepeaterLogic qui est actif.
//echo "$LOGICS\n";     //Contien les logiques SimplexLogic ou RepeaterLogic qui sont actives.
//echo "$LOGICSR\n";    //Affiche ReflectorLogic s'il est actif.
//echo "$callsignR\n" : "";  //Contien le CALLSIGN de ReflectorLogic sans guillemets si celui-ci est défini.
//echo "$LOGICSC\n";    //Affiche 'Connected' si ReflectorLogic est actif, sinon 'Not Connected'

?>
