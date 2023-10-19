<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velez Beatriz Tablas PHP</title>
</head>

<body>
    <h1>Todas las películas</h1>

    <!-- CONNECT BBDD -->
    <?php require('connect.php'); ?>

    <!-- ACTIONS -->
    <?php

    // Query add reviews
    $query_reviews = ' INSERT INTO reviews ( review_movie_id, review_date, reviewer_name, review_comment, review_rating) VALUES 
    ( 1, "2023-09-23", "Beatriz Vélez", "Le falta acción pero los efectos especiales buenisimos", 4),
    ( 1, "2022-11-23", "Raquel Mesa", "No me ha gustado nada de nada...no se parece ni al trailer", 2),
    ( 1, "2021-09-23", "Oriol Casanova i Palmer", "Me ha encantado este pelicula es muy sexy", 5)';

    executeQuery($query_reviews);
    echo '<p>¡Se han añadido 3 nuevas reseñas!</p>';
    ?>
    <a href="<?php echo URL_SITE; ?>index.php">Volver al menú principal</a>

</body>

</html>