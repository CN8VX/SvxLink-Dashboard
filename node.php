<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
if ( !file_exists('include/config.php') ) { die("ERROR: File include/config.php not found...exiting"); }
else { include_once 'include/config.php'; }
include_once 'include/tools.php';
include_once 'include/infosvx.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
<head>
    <meta name="robots" content="index" />
    <meta name="robots" content="follow" />
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="SVXLink" />
    <meta name="Author" content="SP2ONG, SP0DZ" />
    <meta name="Description" content="Dashboard SVXLink by CN8VX" />
    <meta name="KeyWords" content="Dashboard,SVXLink,CN8VX" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Fredoka+One&family=Tourney&family=Oswald&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">

<?php echo ("<title>Dashboard for " . $CALLSIGN ." run in ". $LOGICS . "</title>"); ?>
<?php include_once "include/browserdetect.php"; ?>
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/pcm-player.min.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" type="text/css" rel="stylesheet" />
    <script src="scripts/featherlight.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
    
<?php include("./include/top_header.php") ?>
</div></div>
</div>
<?php include_once __DIR__."/include/top_menu.php"; ?>

<div class="content"><center>
<div style="margin-top:8px;">
</div></center>
</div>

<?php
    echo '<table style="margin-bottom:0px;border:0; border-collapse:collapse; cellspacing:0; cellpadding:0; background-color:#f1f1f1;"><tr style="border:none;background-color:#f1f1f1;">';
    echo '<td width="200px" valign="top" class="hide" style="height:auto;border:0;background-color:#f1f1f1;">';
    echo '<div class="nav" style="margin-bottom:10px;margin-top:0px;">'."\n";

    echo '<script type="text/javascript">'."\n";
    echo 'function reloadStatusInfo(){'."\n";
    echo '  $("#statusInfo").load("include/status.php",function(){ setTimeout(reloadStatusInfo,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadStatusInfo,3000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="statusInfo" style="margin-bottom:30px;">'."\n";
    include 'include/status.php';
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</td>'."\n";

    echo '<td valign="top" style="height:auto;border:none;  background-color:#f1f1f1;">';
    echo '<div class="content">'."\n";
    echo '<script type="text/javascript">'."\n";

    if (URLSVXRAPI!="") {
    echo 'function reloadSVXREF(){'."\n";
    //echo '  $("#svxref").load("include/svxref.php",function(){ setTimeout(reloadSVXREF,90000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSVXREF,90000);'."\n";
     }

    echo 'function reloadLastHerd(){'."\n";
    echo '  $("#lastHerd").load("include/lh_small.php",function(){ setTimeout(reloadLastHerd,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadLastHerd,3000);'."\n";

    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<center><div id="lastHerd" style="margin-bottom:30px;">'."\n";
    include 'include/lh_small.php';
    echo '</div></center>'."\n";
    echo "<br />\n";
    if (URLSVXRAPI!="") {
    echo '<center><div id="svxref" style="margin-bottom:30px;">'."\n";
    //include 'include/svxref.php';
    echo '</div></center>'."\n";
    }
    echo '</td>';
?>
</tr></table>
<?php
    echo '<div class="content2">'."\n";
    echo '<script type="text/javascript">'."\n";
    echo 'function reloadSysInfo(){'."\n";
    echo '  $("#sysInfo").load("include/system.php",function(){ setTimeout(reloadSysInfo,15000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSysInfo,15000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="sysInfo">'."\n";
    include 'include/system.php';
    echo '</div>'."\n";
    echo '</div>'."\n";
?>

<?php include("./include/footer.php") ?>
</div>
</div>
</fieldset>
<br>
</body>
</html>
