<?php
include 'include/functions.php';
include 'include/config.php'; // Inclure le fichier de configuration

session_start();

// Si le login n'est pas requis, rediriger directement vers index.php
if (!$CONFIG['login_required']) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = "Invité"; // Utilisateur par défaut quand le login est désactivé
    header("Location: index.php");
    exit();
}

// Utiliser les utilisateurs définis dans config.php
$USERS = $CONFIG['users'];

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    if (isset($USERS[$user]) && $USERS[$user] === $pass) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user; // Pour afficher le nom de l'utilisateur
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="DMR-MAROC" content="Amaya, see https://www.dmr-maroc.com/" />
    <!-- Balises meta et informations de la page -->
    <meta name="title" content="Connection logs for AllStar and EchoLink By CN8VX" />
    <meta name="description" content="Login page for AllStar and EchoLink logs" />
    <meta property="og:image" content="img/M.A.R.R.I_trans.png">
    <link rel="shortcut icon" href="img/M.A.R.R.I_trans.png">
    <!-- css et script -->
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>
<body>
<div class="logtile-container">
    <a><img class="icm" src="<?php echo LOGO_PATH; ?>" alt="Logo"></a>
    <span class="logo-title"><?php echo $TITLEBAN; ?></span>
</div>
  <br>
    <h1>Login</h1>
    &nbsp;
    <div class="cetr">
    <?php if (!empty($error)) { echo "<p class='text02 clr-red';'>$error</p>"; } ?>
    <form method="post">
        <label class="text02">User name:<br>
        <input type="text" name="username" autocomplete="username" required>
        </label><br><br>
    &nbsp;<br>
        <label class="text02">Password:<br>
        <input type="password" name="password" autocomplete="current-password" required>
        </label><br><br>
    &nbsp;<br>
        <button class="button-01" type="submit">Log in</button>
    </form>
    </div>
</body>
<!-- Début du Footer -->
<?php include 'include/footer.php'; ?> 
<!-- Fin du Footer -->
</html>