<?php
/**
 * Function to check if EchoLink module is active in SVXLink configuration
 * @param string $svxlink_config Path to SVXLink configuration file
 * @return bool Returns true if EchoLink is active, false otherwise
 */
function isEchoLinkActive($svxlink_config) {
    if (!file_exists($svxlink_config)) {
        return false;
    }

    $config_content = file_get_contents($svxlink_config);
    
    // Check if SimplexLogic or RepeaterLogic is used
    preg_match('/\[GLOBAL\][^\[]*LOGICS\s*=\s*([^\n\r]+)/si', $config_content, $logics_matches);
    
    if (!isset($logics_matches[1])) {
        return false;
    }
    
    $logics = $logics_matches[1];
    $has_simplex = strpos($logics, 'SimplexLogic') !== false;
    $has_repeater = strpos($logics, 'RepeaterLogic') !== false;
    
    // Check if ModuleEchoLink is enabled in the appropriate section
    if ($has_simplex) {
        preg_match('/\[SimplexLogic\][^\[]*MODULES\s*=\s*([^\n\r]+)/si', $config_content, $modules_matches);
        if (isset($modules_matches[1]) && strpos($modules_matches[1], 'ModuleEchoLink') !== false) {
            return true;
        }
    }
    
    if ($has_repeater) {
        preg_match('/\[RepeaterLogic\][^\[]*MODULES\s*=\s*([^\n\r]+)/si', $config_content, $modules_matches);
        if (isset($modules_matches[1]) && strpos($modules_matches[1], 'ModuleEchoLink') !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Parse log files to extract EchoLink connection data
 * @param array $log_files Array of log file paths
 * @param int $limit Optional limit of how many entries to return (0 = all)
 * @return array Array of log entries
 */
function parseEchoLinkLogs($log_files, $limit = 0) {
    $log_entries = [];
    
    // Store connections by callsign for reference
    $connections = [];
    
    foreach ($log_files as $log_file) {
        if (!file_exists($log_file)) {
            continue;
        }
        
        $file_content = file_get_contents($log_file);
        $lines = explode("\n", $file_content);
        
        $current_indicatif = null;
        $current_node = null;
        $current_ip = null;
        
        foreach ($lines as $line) {
            // Parse date
            if (preg_match('/^([A-Za-z]{3}\s+[A-Za-z]{3}\s+\d+\s+\d{2}:\d{2}:\d{2}\s+\d{4}):\s+(.+)$/', $line, $matches)) {
                $date = $matches[1];
                $content = $matches[2];
                
                // Incoming connection
                if (preg_match('/Incoming EchoLink connection from ([A-Za-z0-9-]+) \(.*\) at ([0-9.]+)/', $content, $conn_matches)) {
                    $current_indicatif = $conn_matches[1];
                    $current_ip = $conn_matches[2];
                    // Save connection info
                    $connections[$current_indicatif] = [
                        'ip' => $current_ip,
                        'node' => $current_node ?? ''
                    ];
                }
                
                // Accepting connection with ID
                if (preg_match('/([A-Za-z0-9-]+): Accepting connection. EchoLink ID is (\d+)/', $content, $id_matches)) {
                    $current_indicatif = $id_matches[1];
                    $current_node = $id_matches[2];
                    // Update connection info
                    if (!isset($connections[$current_indicatif])) {
                        $connections[$current_indicatif] = ['ip' => ''];
                    }
                    $connections[$current_indicatif]['node'] = $current_node;
                }
                
                // Connection state changes
                if (preg_match('/([A-Za-z0-9-]+): EchoLink QSO state changed to CONNECTED/', $content, $connect_matches)) {
                    $call_sign = $connect_matches[1];
                    $formatted_date = formatDate($date);
                    
                    // Use stored connection info
                    $node_id = '';
                    $ip_addr = '';
                    
                    if (isset($connections[$call_sign])) {
                        $node_id = $connections[$call_sign]['node'] ?? '';
                        $ip_addr = $connections[$call_sign]['ip'] ?? '';
                    }
                    
                    $log_entries[] = [
                        'date' => $formatted_date,
                        'action' => 'Connected',
                        'node' => $node_id,
                        'indicatif' => $call_sign,
                        'ip' => $ip_addr
                    ];
                }
                
                // Disconnection state changes
                if (preg_match('/([A-Za-z0-9-]+): EchoLink QSO state changed to DISCONNECTED/', $content, $disconnect_matches)) {                    
                    $call_sign = $disconnect_matches[1];
                    $formatted_date = formatDate($date);
                    
                    // Use stored connection info
                    $node_id = '';
                    $ip_addr = '';
                    
                    if (isset($connections[$call_sign])) {
                        $node_id = $connections[$call_sign]['node'] ?? '';
                        $ip_addr = $connections[$call_sign]['ip'] ?? '';
                    }
                    
                    $log_entries[] = [
                        'date' => $formatted_date,
                        'action' => 'Disconnected',
                        'node' => $node_id,
                        'indicatif' => $call_sign,
                        'ip' => $ip_addr
                    ];
                }
            }
        }
    }
    
    // Sort by date (latest first)
    usort($log_entries, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    // Apply limit if needed
    if ($limit > 0 && count($log_entries) > $limit) {
        $log_entries = array_slice($log_entries, 0, $limit);
    }
    
    return $log_entries;
}

/**
 * Format date from log format to dd-mm-yyyy hh:mm:ss
 * @param string $date Date in format "Tue May 6 18:03:23 2025"
 * @return string Formatted date "06-05-2025 18:03:23"
 */
function formatDate($date) {
    $timestamp = strtotime($date);
    return date('d-m-Y H:i:s', $timestamp);
}

/**
 * Get base callsign without -L or -R suffix
 * @param string $callsign Callsign possibly with suffix
 * @return string Base callsign
 */
function getBaseCallsign($callsign) {
    // Remove -L or -R suffix if present
    return preg_replace('/-[LR]$/', '', $callsign);
}

/**
 * Render the EchoLink log table
 * @param array $log_entries Array of log entries
 * @return string HTML for the table
 */
function renderLogTable($log_entries, $show_ip = true) {
    $html = '<table class="log-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Node</th>
                <th>Indicatif</th>';
    
    // Only add the IP column header if show_ip is true
    if ($show_ip) {
        $html .= '<th>IP</th>';
    }
    
    $html .= '</tr>
        </thead>
        <tbody>';
    
    if (empty($log_entries)) {
        $colspan = $show_ip ? 5 : 4;
        $html .= '<tr><td colspan="' . $colspan . '">No EchoLink connection found in logs</td></tr>';
    } else {
        foreach ($log_entries as $entry) {
            $base_callsign = getBaseCallsign($entry['indicatif']);
            $html .= '<tr>
                <td>' . htmlspecialchars($entry['date']) . '</td>
                <td class="action">' . htmlspecialchars($entry['action']) . '</td>
                <td>' . htmlspecialchars($entry['node']) . '</td>
                <td><a class="lien" href="https://www.qrz.com/db/' . htmlspecialchars($base_callsign) . '" target="_blank">' . 
                    htmlspecialchars($entry['indicatif']) . '</a></td>';
            
            // Only add the IP cell if show_ip is true
            if ($show_ip) {
                $html .= '<td>' . htmlspecialchars($entry['ip']) . '</td>';
            }
            
            $html .= '</tr>';
        }
    }
    
    $html .= '</tbody></table>';
    
    return $html;
}
