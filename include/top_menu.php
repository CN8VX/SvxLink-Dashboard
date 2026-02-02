<p style="padding-right: 5px; text-align: right; color: #000000;"> 
	<a href="/" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Dashboard</a> |
	<a href="/node.php" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Nodes</a> | 
	<a href="/tg.php" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Talk Groups</a> | 

<?php 
$svxConfigFile = SVXCONFPATH . "/" . SVXCONFIG;
$svxconfig = parse_svxlink_config($svxConfigFile);

$isEchoLink = false;

if (!empty($svxconfig) && isset($svxconfig['GLOBAL']['LOGICS'])) {

    $logicsRaw = $svxconfig['GLOBAL']['LOGICS'];
    $logics = explode(",", $logicsRaw);

    // Déterminer le type de logique active
    $isSimplex   = false;
    $isRepeater  = false;
    $isTetra     = false;

    foreach ($logics as $key) {
        $key = trim($key);
        if ($key == "SimplexLogic")  $isSimplex  = true;
        if ($key == "RepeaterLogic") $isRepeater = true;
        if ($key == "TetraLogic")    $isTetra    = true;
    }

    // Récupérer les modules selon la logique active
    $modules = [];
    if ($isSimplex  && isset($svxconfig['SimplexLogic']['MODULES']))  $modules = explode(",", str_replace('Module', '', $svxconfig['SimplexLogic']['MODULES']));
    if ($isRepeater && isset($svxconfig['RepeaterLogic']['MODULES'])) $modules = explode(",", str_replace('Module', '', $svxconfig['RepeaterLogic']['MODULES']));
    if ($isTetra    && isset($svxconfig['TetraLogic']['MODULES']))    $modules = explode(",", str_replace('Module', '', $svxconfig['TetraLogic']['MODULES']));

    // Vérifier si EchoLink est dans les modules
    foreach ($modules as $key) {
        if (trim($key) == "EchoLink") $isEchoLink = true;
    }
}

// Afficher le menu
echo "<a href=\"/logsvx\" target=\"_blank\" style=\"color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> Log</a> |";

if ($isEchoLink) {
    echo " <a href=\"/echolink.php\" style=\"color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;\">EchoLink</a> |";
}

echo "<a href=\"http://51.210.47.236\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> SvxReflector</a> |";
echo "<a href=\"http://135.125.205.162/supermon/link.php?nodes=492510,492511,58998,588891,590820\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> AllStarLink HUB</a> |";
echo "<a href=\"http://6041.adn.systems\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> ADN MAROC</a> |";
echo "<a href=\"http://135.125.205.162:8080\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> FreeDMR MAROC</a>";
?>
</p>