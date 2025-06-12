<?php
// Inclure la configuration
include_once 'config.php';
include_once 'systems/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<!-- Début head -->
<head>
    <meta charset="utf-8" />
	<meta name="robots" content="index" />
    <meta name="keywords" content="LOG SvxLink by CN8VX svxnink, SvxLink, LOG SvxLink par CN8VX, CN8VX, " /><!-- Pour les moteurs de recherche -->
    <meta name="viewport" content="width=device-width, initial-scale=1" /> <!-- format mobile avec CSS3 -->
    <meta name="LOG SvxLink" content="Amaya, see https://www.dmr-maroc.com/repeaters_simplex_svxlink.php" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <!-- Balises meta et informations de la page -->
	<meta name="title" content="LOG SvxLink By CN8VX" />
	<meta name="description" content="Log SvxLink créer par CN8VX, SvxLink log created by CN8VX." />
	<!-- Balises Open Graph pour l'aperçu -->
	<meta property="og:image" content="img/M.A.R.R.I trans.png">
	<meta property="og:url" content="https://www.dmr-maroc.com/repeaters_simplex_svxlink.php">
	<!-- fin Balises Open Graph pour l'aperçu -->
	<link rel="shortcut icon" href="img/M.A.R.R.I trans.png">
	<title>LOG SvxLink for <?php echo $CALLSIGN; ?> Repeater</title>
	<!-- css et script -->
	<link rel="stylesheet" href="css/styles.css">
    <script src="scripts/idxscripts.js"></script> 
    <!-- css et script -->
</head>
<!-- fin head -->

<body>
	<div class="top-container">
		<h1>SvxLink Log for <a class="cl-h1"><?php echo $CALLSIGN;?></a> Repeater. using Active Logics: <a class="cl-h1"><?php echo $LOGICS;?></a>.</h1>
		<h2>Is <a><?php echo $LOGICSC;?></a> in SVXReflector with: <a class="cl-h1"><?php echo $callsignR;?></a></h2>
		
	</div>
<!-- debut tools-Hardware Info -->	
	<div class="header" id="myHeader">
		<center>
			<div id="hardwareInfoContainer">
				<?php include 'hardinfo.php'; ?>
			</div>
		</center>
	</div>
<!-- fin tools-Hardware Info -->	
	&nbsp;
<div class="content">
		<h2>File Log for Repeater <?php echo $CALLSIGN;?></h2>
</div>
<pre id="updatedlog">
<?php
	/// Code PHP initial pour générer le contenu
	$zeilen1 = file($LOGFILES);
	$anzahl = count($zeilen1);
	// Inverse l'ordre d'affichage
	for ($i = $anzahl - 1; $i >= $anzahl - 350 && $i >= 0; $i--) {
	echo "{$zeilen1[$i]}";
	}
?>
</pre>

</body>
<br><br>   
	<!-- Début du Footer -->
<footer class="fixed-footer">
	<div>
		<p>Copyright © <?php {$cdate="2023 - ".date("Y");} echo $cdate; ?> for Moroccan Repeaters Interco. Version <b>3.2</b> Created and Designed By: <a class="lien" target="_blank" href="mailto: cn8vx.ma@gmail.com">CN8VX</a>, Youness sysop of <a target="_blank" href="http://135.125.205.162:8080/">Serveur DMR-MAROC</a>. All rights reserved.
		<span><a target="_blank" href="http://51.210.47.236/">SvxReflector for Moroccan AmteurRadio Repeaters Interco.</a><br></span></p>
	</div>
</footer>
    <!-- fin du Footer -->


</html>