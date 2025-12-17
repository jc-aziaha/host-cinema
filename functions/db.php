<?php

if (!function_exists('connectToDb')) {
    
    /**
     * Cette fonction établie une connexion avec la base de données.
     *
     * @return PDO
     */
    function connectToDb(): PDO {
        $dsnDb = 'mysql:dbname=bim;host=127.0.0.1';
        $userDb = 'root';
        $passwordDb = '';

        try {
            $db = new PDO($dsnDb, $userDb, $passwordDb);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Error connection to db: ' . $e->getMessage());
        }

        return $db;
    }
}

if (!function_exists('createFilm')) {

    /**
     * Cette fonction permet d'insérer un nouveau film en base de données.
     *
     * @param array $data
     * @return void
     */
    function createFilm(null|string $ratingRounded, array $data = []): void {
        $db = connectToDb();

        $req = $db->prepare('INSERT INTO film (title, rating, comment, created_at, updated_at) VALUES (:title, :rating, :comment, now(), now() ) ');
        
        $req->bindValue(":title", $data['title']);
        $req->bindValue(":rating", $ratingRounded);
        $req->bindValue(":comment", isset($data['comment']) ? $data['comment'] : null);

        $req->execute();
        $req->closeCursor();
    }
}

if (!function_exists('getFilms')) {

    /**
     * Cette fonction récupère la liste des films.
     *
     * @return array
     */
    function getFilms(): array {
        $db = connectToDb();

        $req = $db->prepare('SELECT * FROM film ORDER BY created_at DESC');
        $req->execute();
        $films = $req->fetchAll();
        $req->closeCursor();
        
        return $films;
    }
}

if (!function_exists('getFilm')) {

    /**
     * Cette fonction récupère un film en particulier.
     *
     * @return array
     */
    function getFilm(int $filmId): bool|array {
        $db = connectToDb();

        $req = $db->prepare('SELECT * FROM film WHERE id=:id');
        $req->bindValue(":id", $filmId);
        $req->execute();

        $film = $req->fetch();
        $req->closeCursor();
        
        return $film;
    }
}

if (!function_exists('updateFilm')) {

    /**
     * Cette fonction permet d'insérer un nouveau film en base de données.
     *
     * @param array $data
     * @return void
     */
    function updateFilm(null|string $ratingRounded, int $id, array $data = []): void {
        $db = connectToDb();

        $req = $db->prepare('UPDATE film SET title=:title, rating=:rating, comment=:comment, updated_at=now() WHERE id=:id');
        
        $req->bindValue(":title", $data['title']);
        $req->bindValue(":rating", $ratingRounded);
        $req->bindValue(":comment", isset($data['comment']) ? $data['comment'] : null);
        $req->bindValue(":id", $id);

        $req->execute();
        $req->closeCursor();
    }
}

if (!function_exists('deleteFilm')) {

    /**
     * Cette fonction permet d'insérer un nouveau film en base de données.
     *
     * @param int $filmId
     * @return void
     */
    function deleteFilm(int $filmId): void {
        $db = connectToDb();

        $req = $db->prepare('DELETE FROM film WHERE id=:id');
        
        $req->bindValue(":id", $filmId);

        $req->execute();
        $req->closeCursor();
    }
}