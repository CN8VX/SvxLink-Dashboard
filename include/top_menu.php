<p style="padding-right: 5px; text-align: right; color: #000000;"> 
	<a href="/" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Dashboard</a> |
	<a href="/node.php" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Nodes</a> | 
	<a href="/tg.php" style="color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;">Talk Groups</a> | 
	<!-- <a href="/dtmf.php" style="color: #0000ff;">Dtmf</a> | -->
	

<?php 
if (fopen($svxConfigFile,'r'))
{

  $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
  //$svxconfig['TetraLogic']['INIT_PEI'] = $svxconfig['TetraLogic']['INIT_PEI'] . $init_pei_tail;    
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  foreach ($logics as $key) {
	if ($key == "SimplexLogic") $isSimplex = true;
	if ($key == "TetraLogic") $isTetra = true;
	if ($key == "RepeaterLogic") $isTetra = true;
  };
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  if ($isSimplex) $modules = explode(",",str_replace('Module','',$svxconfig['SimplexLogic']['MODULES']));
  if ($isTetra) $modules = explode(",",str_replace('Module','',$svxconfig['TetraLogic']['MODULES']));
  if ($isTetra) $modules = explode(",",str_replace('Module','',$svxconfig['RepeaterLogic']['MODULES']));
  foreach ($modules as $key){
	if ($key == "EchoLink") $isEchoLink = true;
 }
 

//if ($globalRf <> "No")
{
	//echo'	<a href="/rf.php" style="color: #0000ff;"> Rf</a> |';
	echo "<a href=\"/logsvx\" target=\"_blank\" style=\"color: #0000ff; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> Log</a> |" ;
	if ($isEchoLink==true) {echo ' <a href="/echolink.php" style="color: #0000ff;font-family: Oswald, sans-serif; font-size: 14pt;">EchoLink</a> |';};
	$globalRf = $svxconfig['GLOBAL']['RF_MODULE'];
	echo "<a href=\"http://51.91.156.161\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> SvxReflector</a> |" ;
	echo "<a href=\"http://57.131.35.97/allmon3\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> AllStarLink HUB</a> |" ;
	echo "<a href=\"http://6041.adn.systems\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> ADN MAROC</a> |";
	echo "<a href=\"http://57.131.35.97:8080\" target=\"_blank\" style=\"color: #b30000; font-family: 'Oswald', sans-serif; font-size: 14pt;\"> FreeDMR MAROC</a>" ;
}
};
?>
	<!-- <a href="/log.php" style="color: #0000ff;">Log</a> |
	<a href="/update.php" style="color: #0000ff;">Ullllpdate</a> |
	<a href="/power.php" style="color: #0000ff;">Power</a> -->
</p>
