<?php
session_start();

    require_once __DIR__ . "/../functions/helpers.php";
    require_once __DIR__ . "/../functions/db.php";

    /*
     *----------------------------------------------------
     * Traitement des données
     *----------------------------------------------------
    */

    if ("POST" !== $_SERVER['REQUEST_METHOD']) {
        redirectTo('index');
    }
    
    // Récupérer l'identifiant du film à modifier
    if ( !isset($_POST['film_id']) || empty($_POST['film_id']) ) {
        redirectTo('index');
    }

    // Protéger le serveur contre les failles de type xss
    $filmId = (int) htmlspecialchars($_POST['film_id']);

    // Vérifier si l'identifiant récupéré correspond à un film existant
    $film = getFilm($filmId);

    deleteFilm($film['id']);

    $_SESSION['success'] = "Le film a été supprimé";

    redirectTo('index');

