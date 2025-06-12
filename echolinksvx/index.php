<?php
/**
 * Index page for EchoLink SVX
 * Shows the last 10 EchoLink connections
 */

// Include configuration and functions
require_once 'include/config.php';
require_once 'include/functions.php';

// Check if EchoLink is active
$echolink_active = isEchoLinkActive($svxlink_config);

// Parse logs if EchoLink is active
$log_entries = [];
if ($echolink_active) {
    $log_entries = parseEchoLinkLogs($log_files, 10); // Get last 10 entries
}
?>
<?php
session_start();
if ($CONFIG['login_required'] && (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="logs EchoLink créer par CN8VX, Logs for EchoLink created by CN8VX." />
    <meta property="og:image" content="img/M.A.R.R.I_trans.png">
    <link rel="shortcut icon" href="img/M.A.R.R.I_trans.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="scripts/refscripts.js"></script>
    <title>Connection logs for EchoLink Svxlink</title>
</head>
<body>
    <header  class="logtile-container">
        <a><img class="icm" src="<?php echo LOGO_PATH; ?>" alt="Logo"></a>
        <span class="logo-title"><?php echo $TITLEBAN; ?></span>
    </header> 
<!-- Display user information and logout button -->
<?php if ($CONFIG['login_required'] && $CONFIG['show_user_info'] && isset($_SESSION['username'])): ?>
    <div>
        <h2>You are logged in as : <span class="colr-vrf"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h2>
        <a href="logout.php"><button class="button-01">Logout</button></a>
    </div>
    <?php endif; ?>
<br>
    <div class="container">
        <?php if (!$echolink_active): ?>
            <div class="clignote cetr">
                <p class="text-clt">EchoLink is not active on this system.</p>
            </div>
        <?php else: ?>
            <h1>Logs Echolink</h1>
            <div class="nav-links">
                <a target="_blank" href="https://www.echolink.org/validation/node_lookup.jsp" >
                <button class="button-01">Node Number Lookup</button>
                </a>
            </div>
            <div class="cetr1" id="logTable">
                <?php echo renderLogTable($log_entries, $show_ip_column); ?>
            </div>
            
            <div class="view-more cetr">
                <a href="echoLinklog.php" class="btn btn-primary"><button class="button-01">View more</button></a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Début du Footer -->
    <?php include 'include/footer.php'; ?> 
    <!-- Fin du Footer -->
    
    <?php if ($echolink_active): ?>
        <?php include 'include/hardware_Info.php'; ?>
    <script>
        // Set up auto-refresh
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function() {
                refreshLogTable(), refreshHardwareInfo();
            }, <?php echo $refresh_interval * 1000; ?>);
        });
        
    function updateActionStates() {
        const actions = document.querySelectorAll("td.action");
    
        actions.forEach(td => {
        td.classList.remove("connected", "disconnected");
    
        const text = td.textContent.trim().toLowerCase();
    
        if (text === "connected") {
            td.classList.add("connected");
        } else if (text === "disconnected") {
            td.classList.add("disconnected");
        }
        });
    }
        // Apply once on initial page load, Appliquer une fois au chargement initial de la page
        updateActionStates();
    </script>
    <?php endif; ?>
</body>
</html>
