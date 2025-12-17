<?php
session_start();

    var_dump($_SERVER); die();

    require_once __DIR__ . "/../functions/helpers.php";
    require_once __DIR__ . "/../functions/db.php";
    $films = getFilms();

    $_SESSION['csrf_token'] = bin2hex(random_bytes(20));

?>
<?php
    $title = "Liste des films – Répertoire personnel - Cinéma";
    $description = "Découvrez la liste complète de mes films : notes, commentaires et fiches détaillées. Répertoire cinéma mis à jour régulièrement.";
    $keywords = "Cinéma, repertoire, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

    <!-- Main -->
    <main class="container">
        <h1 class="text-center my-3 display-5">Liste des films</h1>

        <div class="d-flex justify-content-end align-items-center mb-3">
            <a href="/create.php" class="btn btn-primary shadow"><i class="fa-solid fa-plus"></i> Ajouter film</a>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <?= $_SESSION['success']; ?>    
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif ?>

                    <?php if(count($films) > 0) : ?>
                        <?php foreach($films as $film) : ?>
                            <article class="film-card rounded shadow p-3 my-4 bg-white">
                                <h2>Titre: <?= htmlspecialchars($film['title']); ?></h2>
                                <p><strong>Note</strong>: <?= $film['rating'] == null ? 'Non renseignée' : displayStars((float) $film['rating']); ?></p>
                                <hr>
                                <div class="d-flex justify-content-start align-items-center">
                                    <a href="/show.php?film_id=<?= htmlspecialchars($film['id']); ?>" class="m-1 btn btn-sm btn-dark">Voir détails</a>
                                    <a href="/edit.php?film_id=<?= htmlspecialchars($film['id']); ?>" class="m-1 btn btn-sm btn-secondary">Modifier</a>
                                    <form action="/delete.php" method="post" class="m-1 d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="honey_pot"  value="">
                                        <input type="hidden" name="film_id"    value="<?= $film['id'] ?>">
                                        <input type="submit" onclick="return confirm('Confirmer la suppression?')" class="btn btn-sm btn-danger" value="Supprimer">
                                    </form>
                                </div>
                            </article>
                        <?php endforeach ?>
                    <?php else : ?>
                        <p class="mt-5 text-center">Aucun film ajouté à la liste.</p>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>
    
<?php include_once __DIR__ . "/../partials/foot.php"; ?>
    
        