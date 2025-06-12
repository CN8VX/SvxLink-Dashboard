<?php
    // Inclure la configuration
    include('config.php');

    $zeilen1 = file($LOGFILES);
    $anzahl = count($zeilen1);
    $nouveauContenu = '';
    // Inverse l'ordre d'affichage
    for ($i = $anzahl - 1; $i >= $anzahl - 1000 && $i >= 0; $i--) {
        $nouveauContenu .= "{$zeilen1[$i]}";
    }
    echo $nouveauContenu;
?>

