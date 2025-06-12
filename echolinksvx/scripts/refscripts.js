/**
 * Script for refreshing log table content
 */

/**
 * Refresh the log table content via AJAX
 */
function refreshLogTable() {
    // Get the current page URL
    const currentUrl = window.location.pathname;
    const isIndex = currentUrl.endsWith('index.php') || currentUrl.endsWith('/');
    
    // Create an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('GET', isIndex ? 'index.php' : 'echoLinklog.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Parse the HTML response
            const parser = new DOMParser();
            const responseDom = parser.parseFromString(xhr.responseText, 'text/html');
            
            // Extract the log table content
            const newLogTable = responseDom.getElementById('logTable');
            
            // Update the current page's table
            if (newLogTable) {
                document.getElementById('logTable').innerHTML = newLogTable.innerHTML;
                // Réappliquer les couleurs après la mise à jour
                updateActionStates(); 
            }
            
        }
    };
    
    xhr.send();
}
function refreshHardwareInfo() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'include/hardware_Info.php', true); // remplace ce fichier par celui qui retourne juste le HTML de #hardwareInfoContainer

    xhr.onload = function () {
        if (xhr.status === 200) {
            const parser = new DOMParser();
            const responseDom = parser.parseFromString(xhr.responseText, 'text/html');
            const newHardwareInfo = responseDom.getElementById('hardwareInfoContainer');
            if (newHardwareInfo) {
                document.getElementById('hardwareInfoContainer').innerHTML = newHardwareInfo.innerHTML;
            }
        }
    };

    xhr.send();
}
