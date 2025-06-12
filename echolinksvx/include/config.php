<?php
/**
 * Configuration file for EchoLink from Svxlink
 */
// Chemin des logs
// Path to SVXLink logs
$log_files = ['/var/log/svxlink','/var/log/svxlink.1'];

// Path to SVXLink configuration file
$svxlink_config = '/etc/svxlink/svxlink.conf';

// Display the banner title.
// Afficher le titre de la bannière.
$TITLEBAN = ("Connection logs for EchoLink from Svxlink");

// Define the logo path.
// Definir le chemin du logo.
define("LOGO_PATH", "img/M.A.R.R.I_trans.png");

// Sysop Name, nom du sysop
$SYSOP = ("CN8VX");

// Refresh interval for the table (in seconds)
$refresh_interval = 5;

// Option pour afficher ou masquer la colonne IP dans les tableaux
// Option to show or hide the IP column in tables
$show_ip_column = true; // true = afficher/show, false = masquer/hide

// login section
$CONFIG = [
    // Enable/disable the login function (true = enabled, false = disabled)
    // Activer/desactiver la fonction de login (true = active, false = desactive)
    'login_required' => false,

    // Show the logged in user and the logout button (true = show, false = hide)
    // This setting only has an effect if login_required is enabled
    // Afficher l'utilisateur connecte et le bouton de deconnexion (true = afficher, false = cacher)
    // Ce paramètre n'a d'effet que si login_required est activé
    'show_user_info' => true,

    // List of users ("username" => "password")
    // These are just examples, you can put any "username" and any "password"
    'users' => [
        "admin" => "admin",
        "user" => "123456",
        "user1" => "user123"
    ]
];

// If login_required is disabled, force show_user_info to false
if (!$CONFIG['login_required']) {
    $CONFIG['show_user_info'] = false;
}
?>
