<?php
// Inclure la configuration
include_once 'config.php';
include_once 'systems/functions.php';

$rawuptime = shell_exec('cat /proc/uptime');
$uptime = format_uptime(substr($rawuptime,0,strpos($rawuptime," ")));

$free_mem=shell_exec("free -m | awk 'NR==2{printf \"%.0f%%\", $3*100/$2 }'");
$disk_used=shell_exec("df -h | awk '\$NF==\"/\"{printf \"%s\",$5}'");

// CPU usage: Green - Less than 75% Yellow - Between 75% to 85% Red - More than 85%
// Utilisation du processeur : Vert - Moins de 75 % Jaune - Entre 75 % et 85 % Rouge - Plus de 85 %
$cpuLoad = sys_getloadavg();
$core_nums = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
$load = round($cpuLoad[0]/($core_nums + 1)*100, 2);
if ($load < 75) { $cpuLoadHTML = "<td style=\"background: #1d1\">".$load."&nbsp;%</td>\n"; }
if ($load >= 75) { $cpuLoadHTML = "<td style=\"background: #fa0\">".$load."&nbsp;%</td>\n"; }
if ($load >= 85) { $cpuLoadHTML = "<td style=\"background: #f00;color: white;\"><b>".$load."&nbsp;% </b></td>\n"; }

if (file_exists('/sys/class/thermal/thermal_zone0/temp')) {
$cpuTempCRaw = exec('cat /sys/class/thermal/thermal_zone0/temp');
if ($cpuTempCRaw !="") {
// if ($cpuTempCRaw > 1000) { 
$cpuTempC = round(abs($cpuTempCRaw)/ 1000)+CPU_TEMP_OFFSET; 
//} else { $cpuTempC = round($cpuTempCRaw); }
 $cpuTempF = round(+$cpuTempC * 9 / 5 + 32);
 if ($cpuTempC < 55) { $cpuTempHTML = "<td style=\"background: #1d1;\">".$cpuTempC."&deg;C</td>\n"; }
 if ($cpuTempC >= 55) { $cpuTempHTML = "<td style=\"background: #fa0;\">".$cpuTempC."&deg;C</td>\n"; }
 if ($cpuTempC >= 70) { $cpuTempHTML = "<td style=\"background: #f00;color:white;\">".$cpuTempC."&deg;C </td>\n"; }
 } else { $cpuTempHTML = "<td style=\"background: #white\">---</td>\n"; }
} else { $cpuTempHTML = "<td style=\"background: #white\">---</td>\n"; }
?>
<!-- Début tableau -->
<p><a><span class="text-info">Hardware Info</span></a><br>
<!--<a><span class="text-vertion"><?php //echo exec($_SERVER['DOCUMENT_ROOT'].'/scripts/platformDetect.sh');?></span></a></p>-->
<fieldset>
<table class="table-containertb ">
  <tr>
	<th>Hostname<br/><span style="font-weight: bold;color:#f7c593;font-size:10px;">IP: <?php echo str_replace(' ', '<br />', exec('hostname -I|awk \'{print $1}\''));?></span></th>
	<th><b>Kernel<br/>release</b></th>
	<th colspan="2">Platform <br><span style="font-weight: bold;color:#ef861c;font-size:12px;">Uptime: <?php echo $uptime; ?></span></th>
	<th><span>&nbsp;<b>Disk&nbsp;<br> used</b></span></th>
	<th><span>&nbsp;<b>Memory&nbsp;<br> used</b></span></th>
	<th><span><b>CPU Usage</b></span></th>
<?php if (file_exists('/sys/class/thermal/thermal_zone0/temp')) {
	echo "<th><span><b>CPU Temp</b></span></th>"; }
?>
  </tr>
  <tr height="24px">
	<td><?php echo php_uname('n');?></td>
	<td><?php echo php_uname('r');?></td>
	<td class="text-vertion" colspan="2"><?php if (!($os = shell_exec('/usr/bin/lsb_release -ds | cut -d= -f2 | tr -d \'"\''))) {
    if(!($os = shell_exec('cat /etc/system-release | cut -d= -f2 | tr -d \'"\''))) {
        if (!($os = shell_exec('find /etc/*-release -type f -exec cat {} \; | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\''))) {
            $os = 'N.A';    }    }	}	$os = trim($os, '"');	$os = str_replace("\n", '', $os);
	echo $os;?></td>
	<!-- <td class="text-vertion" colspan="2"><?php //echo exec('uname');?></td> -->
	<!-- <td class="text-vertion" colspan="2"><?php //echo exec($_SERVER['DOCUMENT_ROOT'].'/scripts/platformDetect.sh');?></td>-->
	<td><?php echo $disk_used;?></td>
	<td><?php echo $free_mem;?></td>
	<!--<td><?php echo $load;?>&nbsp;%<br>(<?php// echo round($cpuLoad[0],1);?> / <?php //echo round($cpuLoad[1],1);?> / <?php //echo round($cpuLoad[2],1);?>)</td>-->
   <?php  echo $cpuLoadHTML;  ?> <!--Affichage de la charge du CPU-->
   <?php if (file_exists('/sys/class/thermal/thermal_zone0/temp')) { echo $cpuTempHTML; } ?> <!--Affichage de la température du CPU-->
  </tr>
</table>
	<?php echo " Server Web:".$_SERVER['SERVER_SOFTWARE']." & PHP ".phpversion(); ?>
</fieldset>

