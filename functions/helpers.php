<?php

if (!function_exists('dd')) {

    /**
     *
     * @param mixed $value
     * @param mixed ...$values
     * 
     * @return void
     */
    function dd(mixed $value, mixed ...$values) {
        var_dump($value, ...$values);
        die();
    }
}

if (!function_exists('dump')) {

    /**
     *
     * @param mixed $value
     * @param mixed ...$values
     * 
     * @return void
     */
    function dump(mixed $value, mixed ...$values) {
        var_dump($value, ...$values);
    }
}

if (!function_exists('redirectTo')) {

    /**
     * Cette fonction effectue une redirection vers la page précisée.
     *
     * @param string $pageName
     * @return void
     */
    function redirectTo(string $pageName) {
        header('Location: ' . $pageName . '.php');
        die();
    }
}

if (!function_exists('displayStars')) {
    function displayStars($rating) {
        // Note sur 5, peut être 0.5, 1, 1.5 ...
        
        // étoile pleine
        $fullStar = '<i class="fas fa-star" style="color: gold;"></i>';    
        
        // demi-étoile
        $halfStar = '<i class="fas fa-star-half-alt" style="color: gold;"></i>'; 
        
        // étoile vide
        $emptyStar = '<i class="far fa-star" style="color: gold;"></i>';   

        $stars = "";
        
        // Arrondir à la demi-étoile la plus proche
        $rating = round($rating * 2) / 2;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars .= $fullStar; // pleine
            } elseif ($i - 0.5 == $rating) {
                $stars .= $halfStar; // demi-étoile
            } else {
                $stars .= $emptyStar; // vide
            }
        }

        return $stars;
    }
}