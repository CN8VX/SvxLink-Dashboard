<?php

function format_uptime_base($seconds, $includeSeconds = false) {
    $secs = intval($seconds % 60);
    $mins = intval($seconds / 60 % 60);
    $hours = intval($seconds / 3600 % 24);
    $days = intval($seconds / 86400);
    $uptimeString = "";

    if ($days > 0) {
        $uptimeString .= $days;
        $uptimeString .= (($days == 1) ? "&nbsp;day" : "&nbsp;days");
    }
    if ($hours > 0) {
        $uptimeString .= (($days > 0) ? ", " : "") . $hours;
        $uptimeString .= (($hours == 1) ? "&nbsp;hr" : "&nbsp;hrs");
    }
    if ($mins > 0) {
        $uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
        $uptimeString .= (($mins == 1) ? "&nbsp;min" : "&nbsp;mins");
    }
    if ($includeSeconds && $secs > 0) {
        $uptimeString .= (($days > 0 || $hours > 0 || $mins > 0) ? ", " : "") . $secs;
        $uptimeString .= (($secs == 1) ? "&nbsp;s" : "&nbsp;s");
    }
    return $uptimeString;
}

function format_time($seconds) {
    return format_uptime_base($seconds, true);
}

function format_uptime($seconds) {
    return format_uptime_base($seconds, false);
}


function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function isProcessRunning($processName, $full = false, $refresh = false) {
  if ($full) {
    static $processes_full = array();
    if ($refresh) $processes_full = array();
    if (empty($processes_full))
      exec('ps -eo args', $processes_full);
  } else {
    static $processes = array();
    if ($refresh) $processes = array();
    if (empty($processes))
      exec('ps -eo comm', $processes);
  }
  foreach (($full ? $processes_full : $processes) as $processString) {
    if (strpos($processString, $processName) !== false)
      return true;
  }
  return false;
}

function aloop() {
      $check_al=exec("lsmod|grep snd_aloop|awk '{print $1}'");
      if (strpos($check_al, "snd_aloop") !== false) {
         return true;
      } else {
      return false; }
}

function cidr_match($ip, $cidr) {
    $outcome = false;
    $pattern = '/^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])\/(\d{1}|[0-2]{1}\d{1}|3[0-2])$/';
    if (preg_match($pattern, $cidr)){
        list($subnet, $mask) = explode('/', $cidr);
        if (ip2long($ip) >> (32 - $mask) == ip2long($subnet) >> (32 - $mask)) {
            $outcome = true;
        }
    }
    return $outcome;
}
?>

<?php
// Chemin vers le fichier de configuration
include_once './config.php';

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
    die("file $configFile not exist,Please enter the exact path to the configuration file.");
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
        $callsignR = 'No Callsing';  // Valeur par défaut
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