<?php
// -------------------------------------------------------
// Déterminer le chemin du fichier de configuration
// -------------------------------------------------------
if (defined('SVXCONFPATH') && defined('SVXCONFIG')) {
    $svxConfigFile = SVXCONFPATH . "/" . SVXCONFIG;
} else {
    $raw = shell_exec("grep CFGFILE /etc/default/svxlink");
    $svxConfigFile = ($raw !== null) ? trim(substr($raw, strrpos($raw, '=') + 1)) : '';
}

// -------------------------------------------------------
// Valeurs par défaut
// -------------------------------------------------------
$callsign     = "N0CALL";
$fmnetwork    = "no registered";
$refApi       = "";
$tgUri        = "";
$nodeInfoFile = "";
$ctcss        = "";
$dtmfctrl     = "";
$system_type  = "";

// -------------------------------------------------------
// Parser le fichier de configuration
// parse_svxlink_config() est définie dans config.php
// -------------------------------------------------------
if (is_file($svxConfigFile) && function_exists('parse_svxlink_config')) {
    $svxconfig = parse_svxlink_config($svxConfigFile);
} else {
    $svxconfig = [];
}

// -------------------------------------------------------
// Extraire les valeurs si le parser a réussi
// -------------------------------------------------------
if (!empty($svxconfig)) {

    // CALLSIGN depuis ReflectorLogic
    $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'] ?? $callsign;

    // Déterminer le type de système (Repeater ou Simplex)
    $logicsRaw = $svxconfig['GLOBAL']['LOGICS'] ?? '';

    if ($logicsRaw !== '') {
        $check_logics = explode(',', $logicsRaw);

        foreach ($check_logics as $logic_key) {
            $logic_key = trim($logic_key);

            if ($logic_key === 'RepeaterLogic' && isset($svxconfig['RepeaterLogic'])) {
                $ctcss       = $svxconfig['RepeaterLogic']['REPORT_CTCSS']    ?? '';
                $system_type = "IS_DUPLEX";
                $dtmfctrl    = $svxconfig['RepeaterLogic']['DTMF_CTRL_PTY']   ?? '';
            }

            if ($logic_key === 'SimplexLogic' && isset($svxconfig['SimplexLogic'])) {
                $ctcss       = $svxconfig['SimplexLogic']['REPORT_CTCSS']     ?? '';
                $system_type = "IS_SIMPLEX";
                $dtmfctrl    = $svxconfig['SimplexLogic']['DTMF_CTRL_PTY']    ?? '';
            }
        }
    }

    // Variables supplémentaires depuis ReflectorLogic
    $refApi       = $svxconfig['ReflectorLogic']['API']           ?? $refApi;
    $fmnetwork    = $svxconfig['ReflectorLogic']['FMNET']         ?? $fmnetwork;
    $tgUri        = $svxconfig['ReflectorLogic']['TG_URI']        ?? $tgUri;
    $nodeInfoFile = $svxconfig['ReflectorLogic']['NODE_INFO_FILE'] ?? $nodeInfoFile;
}
?>