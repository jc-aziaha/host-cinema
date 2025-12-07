<?php
session_start();

    require_once __DIR__ . "/../functions/helpers.php";
    require_once __DIR__ . "/../functions/db.php";

    /*
     *----------------------------------------------------
     * Traitement des données
     *----------------------------------------------------
    */

    // Récupérer l'identifiant du film à modifier
    if ( !isset($_GET['film_id']) || empty($_GET['film_id']) ) {
        redirectTo('index');
    }

    // Protéger le serveur contre les failles de type xss
    $filmId = (int) htmlspecialchars($_GET['film_id']);

    // Vérifier si l'identifiant récupéré correspond à un film existant
    $film = getFilm($filmId);
    if ($film['rating'] == null) {
        $film['rating'] = '';
    }
    if ($film['comment'] == null) {
        $film['comment'] = '';
    }
?>
<?php
    $title = "Lire les détails du film {$film['id']}: {$film['title']}";
    $description = "Les détails du film: {$film['title']}";
    $keywords = "Cinema, repertoire, lire, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

    <!-- Main -->
    <main class="container">
        <h1 class="text-center my-3 display-5">Les détails de ce film</h1>
        <p class="text-center my-4">
            <small>
                Ajouté le <?= (new DateTime($film['created_at']))->format('d/m/Y \à H:i:s'); ?>
            </small>
            <br>
            <small>
                <?php if(isset($film['updated_at']) && !empty($film['updated_at'])) : ?>
                    Modifié le <?= (new DateTime($film['updated_at']))->format('d/m/Y \à H:i:s'); ?>
                <?php endif ?>
            </small>
        </p>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6 mx-auto shadow bg-white p-4 rounded">
                    <article>
                        <h2>Titre: <?= htmlspecialchars($film['title']); ?></h2>
                        <p><strong>Note</strong>: <?= $film['rating'] == null ? 'Non renseignée' : displayStars((float) $film['rating']); ?></p>
                        <p><strong>Commentaire</strong>: <?= $film['comment'] == null ? 'Non renseigné' : nl2br(htmlspecialchars($film['comment'])); ?></p>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>
    
<?php include_once __DIR__ . "/../partials/foot.php"; ?>
    
        