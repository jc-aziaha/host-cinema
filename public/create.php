<?php
session_start();

    require_once __DIR__ . "/../functions/helpers.php";
    require_once __DIR__ . "/../functions/db.php";

    /*
     *----------------------------------------------------
     * Traitement des données
     *----------------------------------------------------
    */

    // 1- Si les données du formulaire sont envoyées via la méthode POST
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
        
        // Alors, 
        // 2- Protéger le serveur contre les failles de type csrf
        if ( 
            !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
            empty($_POST['csrf_token'])  || empty($_SESSION['csrf_token'])  ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token'] 
        ) {
            redirectTo('create');
        }
        unset($_SESSION['csrf_token']);

        // 3- Protéger le serveur contre les robots spameurs
        if (
            !isset($_POST['honey_pot']) || !empty($_POST['honey_pot'])
        ) {
            redirectTo('create');
        }

        // 4- Procéder à la validation des données du formulaire.
        $formErrors = [];

        if (isset($_POST['title'])) {
            $title = trim($_POST['title']);

            if ($title === "") {
                $formErrors['title'] = 'Le titre est obligatoire.';
            } elseif (mb_strlen($title) > 255) {
                $formErrors['title'] = 'Le titre ne doit pas dépasser 255 caractères.';
            } elseif (!preg_match('/^[\p{L}0-9\s\.\,\!\?\-\'":&\/\(\)\[\]\*]+$/u', $title)) {
                $formErrors['title'] = 'Le titre ne doit contenir que des chiffres et des lettres.';
            }
        }

        if (isset($_POST['rating']) && $_POST['rating'] !== "") {
            $rating = trim($_POST['rating']);

            if (!is_numeric($rating)) {
                $formErrors['rating'] = "La note doit être un nombre.";
            } elseif(floatval($rating) < 0 || floatval($rating) > 5) {
                $formErrors['rating'] = "La note doit être comprise entre 0 et 5.";
            }
        }

        if (isset($_POST['comment'])) {
            $comment = trim($_POST['comment']);

            if (mb_strlen($comment) > 1000) {
                $formErrors['comment'] = 'Le commentaire ne doit pas dépasser 1000 caractères.';
            }
        }


        // 5- S'il y a des erreurs, 
        if (count($formErrors) > 0) {
            // Sauvegarder les messages d'erreurs en session
            $_SESSION['form_errors'] = $formErrors;

            // Sauvegarder les anciennes données du formulaire en session
            $_SESSION['old'] = $_POST;

            // Alors, effectuer une redirection vers la page de création,
            // puis arrêter l'exécution du script.
            redirectTo('create');
        }

        
        // Dans le cas contraire,
        // 6- Arrondir la note à un chiffre après la virgule,
        $ratingRounded = null;
        if (isset($_POST['rating']) && $_POST['rating'] !== "") {
            $ratingRounded = round($_POST['rating'], 1);
        }
        
        // 7- Etablir une connexion avec la base de données,
        // 8- Effectuer la requête d'insertion du nouveau film en base de données
        createFilm($ratingRounded, $_POST);
        
        // 9- Générer le message flash de succès de l'opération,
        $_SESSION['success'] = 'Le film a bien été ajouté à la liste.';
        
        // 10- Effectuer une redirection vers la page d'accueil,
        // puis arrêter l'exécution du script.
        redirectTo('index');
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(20));
?>
<?php
    $title = "Nouveau film";
    $description = "Ajout d'un nouveau film";
    $keywords = "Cinema, repertoire, ajout, nouveau, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

    <!-- Main -->
    <main class="container">
        <h1 class="text-center my-3 display-5">Nouveau film</h1>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-4 mx-auto shadow bg-white p-4 rounded">

                    <?php if(isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                <?php foreach($_SESSION['form_errors'] as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach ?>
                                <?php unset($_SESSION['form_errors']); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="title">Titre <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" autofocus value="<?= isset($_SESSION['old']['title']) ? htmlspecialchars($_SESSION['old']['title']) : ''; unset($_SESSION['old']['title']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="rating">Note / 5</label>
                            <input inputmode="decimal" type="number" step=".5" min="0" max="5" id="rating" name="rating" class="form-control" value="<?= isset($_SESSION['old']['rating']) ? htmlspecialchars($_SESSION['old']['rating']) : ''; unset($_SESSION['old']['rating']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="comment">Laissez un commentaire</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4"><?= isset($_SESSION['old']['comment']) ? htmlspecialchars($_SESSION['old']['comment']) : ''; unset($_SESSION['old']['comment']); ?></textarea>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="honey_pot"  value="">
                        <div class="mb-3">
                            <input formnovalidate type="submit" class="btn btn-primary shadow w-100" value="Ajouter">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>
    
<?php include_once __DIR__ . "/../partials/foot.php"; ?>
    
        