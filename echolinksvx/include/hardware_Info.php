<?php
include_once 'config.php';

if (!defined('CPU_TEMP_OFFSET')) {
    define('CPU_TEMP_OFFSET', 0);
}

$uptime = trim(shell_exec("uptime -p"));
$disk_used = trim(shell_exec("df -h / | awk 'NR==2{print $5}'"));
$free_mem = trim(shell_exec("free -h | awk '/Mem:/ {print $3\" / \"$2}'"));

$cpuLoad = sys_getloadavg();
$core_nums = trim(shell_exec("grep -P '^processor' /proc/cpuinfo | wc -l"));
$load = round($cpuLoad[0] / ($core_nums + 1) * 100, 2);

$cpuLoadColor = "#1d1";
if ($load >= 75) $cpuLoadColor = "#fa0";
if ($load >= 85) $cpuLoadColor = "#f00";

$cpuLoadHTML = "<td style='background: $cpuLoadColor; color: " . ($load >= 85 ? "white" : "black") . ";' title='Load avg: {$cpuLoad[0]} / {$cpuLoad[1]} / {$cpuLoad[2]}'><b>{$load}%</b></td>";

$cpuTempAvailable = file_exists('/sys/class/thermal/thermal_zone0/temp');
if (file_exists('/sys/class/thermal/thermal_zone0/temp')) {
    $cpuTempCRaw = exec('cat /sys/class/thermal/thermal_zone0/temp');
    if ($cpuTempCRaw != "") {
        $cpuTempC = round(abs($cpuTempCRaw) / 1000) + CPU_TEMP_OFFSET;
        $cpuTempColor = "#1d1";
        if ($cpuTempC >= 55) $cpuTempColor = "#fa0";
        if ($cpuTempC >= 70) $cpuTempColor = "#f00";
        $cpuTempHTML = "<td style='background: $cpuTempColor; color: black;'><b>{$cpuTempC}&deg;C</b></td>";
    } else {
        $cpuTempHTML = "<td>---</td>";
    }
} else {
    $cpuTempHTML = "<td>---</td>";
}

$os = trim(shell_exec('grep PRETTY_NAME /etc/os-release | cut -d= -f2 | tr -d \'"\''));
if (empty($os)) $os = 'N.A';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- <style>
body {
    /*font-family: Arial, sans-serif;*/
    /*background: #222;*/
    /*color: #eee;*/
    /*text-align: center;*/
    /*margin-bottom: 20px;*/
}
.hardware-info {
    text-align: center;
    margin-top: 20px;
    padding: 20px;
}
.hw-table {
    /*margin: 10px auto;*/
    /*width: 100%;*/             /* Prend toute la largeur du fieldset */
    margin: 10px;               /* Supprime les marges autour */
    max-width: 900px;
    border-collapse: collapse;
    font-size: 14px;
}
.hw-table th, .hw-table td {
    border: 1px solid #444;
    padding: 8px;
    text-align: center;
}
.hw-table th {
    /*background: #333;*/
    font-size: 16px;
    /*color: #f7c593;*/
}
.hardware-info fieldset {
    display: inline-block;
    padding: 10px;
    border: 1px solid #666;
    box-shadow: 0 0 10px #000;
    border-radius: 10px;
    /*text-align: left;*/ /* pour éviter de centrer tout le texte à l’intérieur */
}
.hw-table td:hover {
    background: #cfc7c7;
    transition: 0.3s;
}
.icon {
    font-size: 18px;
}
.text-hw { 
  font-size: 150%;
}
.WebS {
    margin-top:10px;
}
.ip-host {
    font-size: 10px;
}
.platform-os {
    color:#ef861c
}
</style> -->
</head>
<body>
<p><span><?php echo exec($_SERVER['DOCUMENT_ROOT'].'/scripts/platformDetect.sh'); ?></span></p>
<div class="hardware-info" id="hardwareInfoContainer">
<p><span class="text-hw">📟 Hardware Info</span></p>
<fieldset>
<table class="hw-table">
  <tr>
    <th>🖥️ Hostname<br/><span class="ip-host">IP: <?php echo str_replace(' ', '<br />', exec("hostname -I | awk '{print \$1}'"));?></span></th>
    <th>🧬 Kernel Release</th>
    <th colspan="2">💻 Platform<br/><span class="platform-os">Uptime: <?php echo $uptime; ?></span></th>
    <th>📦 Disk<br>Used</th>
    <th>💾 Memory<br>Used</th>
    <th>🧠 CPU<br>Used</th>
	<?php if ($cpuTempAvailable) { echo "<th>🔥 Temp</th>"; } ?>
  </tr>
  <tr>
    <td><?php echo php_uname('n'); ?></td>
    <td><?php echo php_uname('r'); ?></td>
    <td colspan="2"><?php echo $os; ?></td>
    <td><?php echo $disk_used; ?></td>
    <td><?php echo $free_mem; ?></td>
    <?php echo $cpuLoadHTML; ?>
    <?php if ($cpuTempAvailable) { echo $cpuTempHTML; } ?>
  </tr>
</table>
<p class="WebS">🌐 Web Server: <?php echo $_SERVER['SERVER_SOFTWARE']; ?> | PHP <?php echo phpversion(); ?></p>
</fieldset>
</div>
</body>
</html>
