<?php

function format_uptime_base($seconds, $includeSeconds = false) {
  $seconds = (int) $seconds; // Conversion explicite pour éviter les deprecations

  $secs  = $seconds % 60;
  $mins  = intdiv($seconds, 60) % 60;
  $hours = intdiv($seconds, 3600) % 24;
  $days  = intdiv($seconds, 86400);

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
      $uptimeString .= (($secs == 1) ? "&nbsp;sec" : "&nbsp;secs");
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
    return $needle === "" || strpos($haystack, $needle) === 0;
}


function isProcessRunning($processName, $full = false, $refresh = false) {
  if ($full) {
    static $processes_full = array();
    if ($refresh) $processes_full = array();
    if (empty($processes_full)) {
      exec('ps -eo args', $processes_full, $returnCode);
      if ($returnCode !== 0) return false;
    }
  } else {
    static $processes = array();
    if ($refresh) $processes = array();
    if (empty($processes)) {
      exec('ps -eo comm', $processes, $returnCode);
      if ($returnCode !== 0) return false;
    }
  }

  foreach (($full ? $processes_full : $processes) as $processString) {
    if (strpos($processString, $processName) !== false)
      return true;
  }

  return false;
}


function cidr_match($ip, $cidr) {
    if (ip2long($ip) === false) return false;

    $pattern = '/^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])\/(\d{1}|[0-2]{1}\d{1}|3[0-2])$/';
    if (preg_match($pattern, $cidr)) {
        list($subnet, $mask) = explode('/', $cidr);
        if (ip2long($subnet) === false) return false;

        if (ip2long($ip) >> (32 - $mask) == ip2long($subnet) >> (32 - $mask)) {
            return true;
        }
    }

    return false;
}
?>