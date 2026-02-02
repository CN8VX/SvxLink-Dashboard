<?php
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/tools.php';
include_once __DIR__ . '/functions.php';
include_once __DIR__ . '/parse_svxconf.php';
include_once __DIR__ . '/infosvx.php';

// -------------------------------------------------------
// Chemin vers le fichier ModuleEchoLink.conf
// -------------------------------------------------------
$moduleConfigFile = SVXCONFPATH . "/svxlink.d/ModuleEchoLink.conf";

// -------------------------------------------------------
// Parser svxlink.conf avec le parser mutualisé (défini dans config.php)
// -------------------------------------------------------
$configFile = SVXCONFPATH . "/" . SVXCONFIG;
$config = parse_svxlink_config($configFile);

// -------------------------------------------------------
// Vérifier si SimplexLogic ou RepeaterLogic est actif
// -------------------------------------------------------
$activeLogic = null;
if (isset($config['GLOBAL']['LOGICS'])) {
    $logics = explode(',', $config['GLOBAL']['LOGICS']);
    foreach ($logics as $logic) {
        $logic = trim($logic);
        if ($logic === 'SimplexLogic' || $logic === 'RepeaterLogic') {
            $activeLogic = $logic;
            break;
        }
    }
}

// -------------------------------------------------------
// Vérifier si ModuleEchoLink est actif
// -------------------------------------------------------
$moduleActive = false;
if ($activeLogic && isset($config[$activeLogic]['MODULES'])) {
    $modules = explode(',', $config[$activeLogic]['MODULES']);
    $moduleActive = in_array('ModuleEchoLink', array_map('trim', $modules));
}

// -------------------------------------------------------
// Lire la configuration de ModuleEchoLink si actif
// -------------------------------------------------------
$ECLcallSign    = '';
$ECLsysopName   = '';
$ECLlocation    = '';
$ECLdefaultLang = '';

if ($moduleActive && is_file($moduleConfigFile)) {
    $moduleConfig   = parse_svxlink_config($moduleConfigFile);
    $ECLcallSign    = $moduleConfig['ModuleEchoLink']['CALLSIGN']     ?? '';
    $ECLsysopName   = $moduleConfig['ModuleEchoLink']['SYSOPNAME']    ?? '';
    $ECLlocation    = $moduleConfig['ModuleEchoLink']['LOCATION']     ?? '';
    $ECLdefaultLang = $moduleConfig['ModuleEchoLink']['DEFAULT_LANG'] ?? '';
}

// -------------------------------------------------------
// Vérifier les modules depuis parse_svxconf.php
// -------------------------------------------------------
function checkModules($system_type, $svxconfig) {
    if ($system_type == "IS_DUPLEX" && isset($svxconfig['RepeaterLogic']['MODULES'])) {
        return explode(",", str_replace('Module', '', $svxconfig['RepeaterLogic']['MODULES']));
    } elseif ($system_type == "IS_SIMPLEX" && isset($svxconfig['SimplexLogic']['MODULES'])) {
        return explode(",", str_replace('Module', '', $svxconfig['SimplexLogic']['MODULES']));
    }
    return [];
}

function isEchoLinkModuleActive($modules) {
    foreach ($modules as $key) {
        if (trim($key) === "EchoLink") {
            return "True";
        }
    }
    return "False";
}

// -------------------------------------------------------
// Logique principale
// -------------------------------------------------------
$modules = checkModules($system_type, $svxconfig);
$modecho = (!empty($modules)) ? isEchoLinkModuleActive($modules) : "False";

$users      = [];
$echotxing  = '';
$eproxy     = '';
$eproxyd    = '';

if (isProcessRunning('svxlink') && $modecho == "True") {
    $echolog   = getEchoLog();
    $echotxing = getEchoLinkTX();
    $users     = getConnectedEcholink($echolog);

    // Lire PROXY_SERVER depuis ModuleEchoLink.conf
    if (is_file($moduleConfigFile)) {
        $svxeconfig = parse_svxlink_config($moduleConfigFile);
        $eproxyd = $svxeconfig['ModuleEchoLink']['PROXY_SERVER'] ?? '';
    }

    $eproxy = getEchoLinkProxy();
}
?>

<fieldset style="border:#3083b8 2px groove;box-shadow:0 0 10px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:15px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style="color:#B30000;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">NODE EchoLink Information</h1>

<div id="refresh-section">
<table>
    <tr>
        <th width="380px">Echolink Configuration</th>
    </tr>
    <tr>
        <td>
            <table style="border-collapse: collapse; border: none;">
                <tr style="border: none;">
                    <th width="30%"></th>
                    <th width="70%"></th>
                </tr>
                
                <tr style="border: none;"> 
                    <td style="border: none;">Node Callsign</td>
                    <td style="border: none;"><input type="text" id="ECLcallSign" name="ECLcallSign" style="width:98%; color:#0000ff" value="<?php echo $ECLcallSign; ?>" readonly></td>
                </tr>
                
                <tr style="border: none;"> 
                    <td style="border: none;">Node Sysop</td>
                    <td style="border: none;"><input type="text" id="ECLsysopName" name="ECLsysopName" style="width:98%; color:#0000ff" value="<?php echo $ECLsysopName; ?>" readonly></td>
                </tr>
                
                <tr style="border: none;"> 
                    <td style="border: none;">Node Location</td>
                    <td style="border: none;"><input type="text" id="ECLlocation" name="ECLlocation" style="width:98%; color:#0000ff" value="<?php echo $ECLlocation; ?>" readonly></td>
                </tr>
                
                <tr style="border: none;"> 
                    <td style="border: none;">Node Language</td>
                    <td style="border: none;"><input type="text" id="ECLdefaultLang" name="ECLdefaultLang" style="width:98%; color:#0000ff" value="<?php echo $ECLdefaultLang; ?>" readonly></td>
                </tr>
                
                <tr style="border: none;"> 
                    <td style="border: none;">Nodes Connected</td>
                    <td style="border: none;">
                        <input type="text" id="ECLusers" name="ECLusers" style="width:98%; color:#0000ff" value="<?php echo !empty($users) ? implode(', ', $users) : 'No NODE connected'; ?>" readonly>
                    </td>
                </tr>
                
                <tr style="border: none;">
                    <td style="border: none;">Number of Connections</td>
                    <td style="border: none;"><input type="text" id="ECLconnections" name="ECLconnections" style="width:98%; color:#0000ff" value="<?php if (!empty($echotxing)) { echo $echotxing; } else { echo count($users); } ?>" readonly></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>
</center>
</div>
</fieldset>

<script>
// Fonction pour actualiser seulement la section des données
function refreshContent() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "include/echo.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var refreshSection = document.getElementById('refresh-section');
            if (refreshSection) {
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = xhr.responseText;
                var newRefreshSection = tempDiv.querySelector('#refresh-section');
                if (newRefreshSection) {
                    refreshSection.innerHTML = newRefreshSection.innerHTML;
                }
            }
        }
    };
    xhr.send();
}

// Rafraîchit toutes les 3 secondes
setInterval(refreshContent, 3000);
</script>