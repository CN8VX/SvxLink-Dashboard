<?php


function getSVXLog() {
	$logLines1 = array();
	$logLines2 = array();

	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$output = `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `;
		$logLines1 = ($output !== null && $output !== '') ? explode("\n", $output) : [];
	}
	$logLines1 = array_slice($logLines1, -250);

	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
			$output = `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `;
			$logLines2 = ($output !== null && $output !== '') ? explode("\n", $output) : [];
		}
	}
	$logLines2 = array_slice($logLines2, -250);

	$logLines = array_merge($logLines1, $logLines2);
	$logLines = array_slice($logLines, -500);
	return $logLines;
}

function getSVXStatusLog() {
	$logLines1 = array();
	$logLines2 = array();

	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$output = `tail -10000 $logPath | egrep -a -h "EchoLink QSO|ransmitter|Selecting" `;
		$logLines1 = ($output !== null && $output !== '') ? explode("\n", $output) : [];
	}
	$logLines1 = array_slice($logLines1, -250);

	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
			$output = `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `;
			$logLines2 = ($output !== null && $output !== '') ? explode("\n", $output) : [];
		}
	}
	$logLines2 = array_slice($logLines2, -250);

	$logLines = array_merge($logLines1, $logLines2);
	$logLines = array_slice($logLines, -250);
	return $logLines;
}

function getSVXRstatus() {
	$svxrstat = '';

	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$slogPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$svxrstat = `tail -10000 $slogPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;
		$svxrstat = $svxrstat ?? '';
	}

	if ($svxrstat == '' && file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
		$slogPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
		$svxrstat = `tail -10000 $slogPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;
		$svxrstat = $svxrstat ?? '';
	}

	if (strpos($svxrstat, "Authentication OK") !== false || strpos($svxrstat, "Connection established") !== false || strpos($svxrstat, "Activating link") !== false) {
		$svxrstatus = "Connected";
	} elseif (strpos($svxrstat, "Heartbeat timeout") !== false || strpos($svxrstat, "No route to host") !== false || strpos($svxrstat, "Connection refused") !== false || strpos($svxrstat, "Connection timed out") !== false || strpos($svxrstat, "Locally ordered disconnect") !== false || strpos($svxrstat, "Deactivating link") !== false) {
		$svxrstatus = "Not connected";
	} else {
		$svxrstatus = "No status";
	}

	return $svxrstatus;
}

function getEchoLinkProxy() {
	$echoproxy = '';

	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$elogPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$echoproxy = `grep -a -h "EchoLink proxy" $elogPath | tail -1`;
		$echoproxy = $echoproxy ?? '';
	}

	if ($echoproxy == '' && file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
		$elogPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
		$echoproxy = `grep -a -h "EchoLink proxy" $elogPath | tail -1`;
		$echoproxy = $echoproxy ?? '';
	}

	if (strpos($echoproxy, "Connected to EchoLink proxy") !== false) {
		$proxy = substr($echoproxy, strpos($echoproxy, "Connected to EchoLink proxy") + 27);
		$eproxy = "Connected to proxy<br><span style=\"color:brown;font-weight:bold;\">" . $proxy . "</span>";
	} elseif (strpos($echoproxy, "Disconnected from EchoLink proxy") !== false) {
		$proxy = substr($echoproxy, strpos($echoproxy, "Disconnected from EchoLink proxy") + 32);
		$eproxy = "<span style=\"color:red;font-weight:bold;\">Disconnected proxy</span><br><span style=\"color:brown;font-weight:bold;\">" . $proxy . "</span>";
	} elseif (strpos($echoproxy, "Access denied to EchoLink proxy") !== false) {
		$eproxy = "Access denied to proxy";
	} else {
		$eproxy = "";
	}

	return $eproxy;
}


function getEchoLog() {
	$echolog = [];

	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$elogPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$output = `grep -a -h "EchoLink QSO" $elogPath`;
		$echolog = ($output !== null && $output !== '') ? explode("\n", $output) : [];
	}

	$echolog = array_slice($echolog, -500);
	return $echolog;
}

function getConnectedEcholink($echolog) {
	$users = Array();
	foreach ($echolog as $ElogLine) {
		if (strpos($ElogLine, "state changed to CONNECTED") !== false) {
			$lineParts = explode(" ", $ElogLine);
			$t_EL_KeyPos = array_search('QSO', $lineParts) - 2;
			if ($t_EL_KeyPos >= 0 && isset($lineParts[$t_EL_KeyPos])) {
				$call = trim(substr($lineParts[$t_EL_KeyPos], 0, -1));
				if (!in_array($call, $users)) {
					array_push($users, $call);
				}
			}
		}
		if (strpos($ElogLine, "state changed to DISCONNECTED") !== false) {
			$lineParts = explode(" ", $ElogLine);
			$t_EL_KeyPos = array_search('QSO', $lineParts) - 2;
			if ($t_EL_KeyPos >= 0 && isset($lineParts[$t_EL_KeyPos])) {
				$call = trim(substr($lineParts[$t_EL_KeyPos], 0, -1));
				$pos = array_search($call, $users);
				if ($pos !== false) {
					array_splice($users, $pos, 1);
				}
			}
		}
	}
	return $users;
}

function getEchoLinkTX() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	$echotxing = "";

	if (!file_exists($logPath)) return $echotxing;

	$logLine = `tail -10000 $logPath | egrep -a -h "### EchoLink" | tail -1`;
	$logLine = $logLine ?? '';

	if ($logLine !== '' && strpos($logLine, "### EchoLink talker start") !== false) {
		$echotxing = substr($logLine, strpos($logLine, "start") + 6, 12);
	}

	return $echotxing;
}


function getSVXTGSelect() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	$tgselect = "0";

	if (!file_exists($logPath)) return $tgselect;

	$logLine = `tail -10000 $logPath | egrep -a -h "Selecting" | tail -1`;
	$logLine = $logLine ?? '';

	if ($logLine !== '' && strpos($logLine, "TG #") !== false) {
		$tgselect = trim(substr($logLine, strpos($logLine, "#") + 1, 12));
	}

	return $tgselect;
}


function getSVXTGTMP() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	$tgselect = "";

	if (!file_exists($logPath)) return $tgselect;

	$logLine = `tail -10000 $logPath | egrep -a -h "emporary monitor" | tail -1`;
	$logLine = $logLine ?? '';

	if ($logLine !== '' && strpos($logLine, "Add") !== false) {
		$tgselect = substr($logLine, strpos($logLine, "#") + 1, 12);
	}

	return trim($tgselect);
}


function initModuleArray() {
	$modules = Array();
	foreach (SVXMODULES as $enabled) {
		$modules[trim($enabled)] = 'Off';
	}
	return $modules;
}

function getActiveModules() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	$logLines = [];

	if (file_exists($logPath)) {
		$output = `tail -10000 $logPath | egrep -a -h "Activating module|Deactivating module" `;
		$logLines = ($output !== null && $output !== '') ? explode("\n", $output) : [];
	}

	$logLines = array_slice($logLines, -250);
	$modules = initModuleArray();

	foreach ($logLines as $logLine) {
		if (strpos($logLine, "Activating module") !== false) {
			$lineParts = explode(" ", $logLine);
			$t_MOD_KeyPos = array_search('module', $lineParts);
			if ($t_MOD_KeyPos !== false && isset($lineParts[$t_MOD_KeyPos + 1])) {
				$modul = substr($lineParts[$t_MOD_KeyPos + 1], 0, -3);
				$modules[$modul] = 'On';
			}
		}
		if (strpos($logLine, "Deactivating module") !== false) {
			$lineParts = explode(" ", $logLine);
			$t_MOD_KeyPos = array_search('module', $lineParts);
			if ($t_MOD_KeyPos !== false && isset($lineParts[$t_MOD_KeyPos + 1])) {
				$modul = substr($lineParts[$t_MOD_KeyPos + 1], 0, -3);
				$modules[$modul] = 'Off';
			}
		}
	}

	return $modules;
}


function getHeardList($logLines) {
	$heardList = array();

	foreach ($logLines as $logLine) {
		if (strpos($logLine, "Tx1") !== false || strpos($logLine, "Rx1") !== false || strpos($logLine, ": Talker start on") !== false || strpos($logLine, ": Talker stop on") !== false) {

			$callsign  = '';
			$target    = '';
			$source    = 'SVXRef';
			$timestamp = substr($logLine, 0, 19);
			$tx        = 'OFF';

			if (strpos($logLine, ": Talker stop on") !== false) {
				$calltemp = substr($logLine, strpos($logLine, "TG") + 4, 27);
				$callsign = trim(substr($calltemp, strpos($calltemp, ":") + 1, 27));
				$target   = "TG " . trim(get_string_between($logLine, "#", ":"));
				$tx       = "OFF";
			}

			if (strpos($logLine, ": Talker start on") !== false) {
				$calltemp = substr($logLine, strpos($logLine, "TG") + 4, 27);
				$callsign = trim(substr($calltemp, strpos($calltemp, ":") + 1, 27));
				$target   = "TG " . trim(get_string_between($logLine, "#", ":"));
				$tmss     = strtotime($timestamp);
				$tmst     = strtotime('now');
				$diff     = $tmst - $tmss;
				$tx       = ($diff > 300) ? "OFF" : "ON";
			}

			if (strlen($callsign) > 0 && strlen($callsign) < 16) {
				array_push($heardList, array($timestamp, $callsign, $target, $tx, $source));
			}
		}
	}

	return $heardList;
}

function getLastHeard($logLines) {
	$lastHeard  = array();
	$heardCalls = array();
	$heardList  = getHeardList($logLines);

	foreach ($heardList as $listElem) {
		if ($listElem[4] == "SVXRef") {
			$callUuid = $listElem[1] . "#" . $listElem[2];
			if (array_search($callUuid, $heardCalls) === false) {
				array_push($heardCalls, $callUuid);
				array_push($lastHeard, $listElem);
			}
		}
	}

	return $lastHeard;
}


function getTXInfo() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;

	if (!file_exists($logPath)) {
		return "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n";
	}

	$txstat = `tail -10000 $logPath | egrep -a -h "Turning the transmitter|squelch is|squelch for" | tail -1`;
	$txstat = $txstat ?? '';

	if ($txstat !== '' && strpos($txstat, "ON") !== false) {
		return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TX</div></td></tr>\n";
	}

	if ($txstat !== '' && strpos($txstat, "OPEN") !== false) {
		return "<tr><td style=\"background:#4aa361;color:black;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">RX</div></td></tr>\n";
	}

	return "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n";
}


function getRXPeak() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;

	if (!file_exists($logPath)) {
		return "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Peak OK</div></td></tr>\n";
	}

	$txstat = `tail -100 $logPath | egrep -a -h "Distortion detected!" | tail -1`;
	$txstat = $txstat ?? '';

	if ($txstat !== '') {
		$timestamp = substr($txstat, 0, 19);
		$tmss = strtotime($timestamp);
		$tmst = strtotime('now');
		$diff = $tmst - $tmss;

		if (strpos($txstat, "Distortion") !== false && $diff < 1) {
			return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">DISTORTION</div></td></tr>\n";
		}
	}

	return "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Peak OK</div></td></tr>\n";
}

function getConfigItem($section, $key, $configs) {
	$sectionpos = array_search("[" . $section . "]", $configs);
	if ($sectionpos === false) return null;

	$sectionpos++;
	$len = count($configs);

	while ($sectionpos < $len) {
		if (startsWith($configs[$sectionpos], "[")) {
			return null;
		}
		if (startsWith($configs[$sectionpos], $key . "=")) {
			return substr($configs[$sectionpos], strlen($key) + 1);
		}
		$sectionpos++;
	}

	return null;
}

function get_string_between($string, $start, $end) {
	$string = " " . $string;
	$ini = strpos($string, $start);
	if ($ini === false || $ini == 0) {
		return "";
	}
	$ini += strlen($start);
	$endPos = strpos($string, $end, $ini);
	if ($endPos === false) {
		return "";
	}
	$len = $endPos - $ini;
	return substr($string, $ini, $len);
}

$logLinesSVX = getSVXLog();
$reverseLogLinesSVX = $logLinesSVX;
array_multisort($reverseLogLinesSVX, SORT_DESC);
$lastHeard = getLastHeard($reverseLogLinesSVX);

?>