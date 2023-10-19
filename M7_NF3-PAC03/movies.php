<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velez Beatriz Tablas PHP</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>Todas las películas</h1>
    <!-- CONNEXION BBDD -->
    <?php require('connect.php'); ?>
    <?php

    //Query select 
    $query_information = 'SELECT movie_id, movie_name, movie_year, movie_director, movie_leadactor, movie_type FROM  movie ORDER BY  movie_name ASC, movie_year DESC';
    //Call this function to extract data
    $data = extractData($query_information);

    if($data->num_rows == 0):
        
        ?> <h2>¡Esta tabla no tiene contenido...!</h2> <?php
        @$bbdd->close;
        exit;

    else:
        $total_movies = $data->num_rows;
        ?>
        <h1>Las mejores películas según los expertos</h1>
        <table>
            <thead>
                <tr>
                    <th>Movie Title</th>
                    <th>Year of Release</th>
                    <th>Movie Director</th>
                    <th>Movie Lead Actor</th>
                    <th>Movie Type</th>
                </tr>
            </thead>
            <tbody>
        <?php
        while( $row = $data->fetch_assoc()):
            //Extract row
            extract($row);

            //Call functions extract exact data
            $director = get_director($movie_director);
            $leadactor = get_leadactor($movie_leadactor);
            $movietype = get_movietype($movie_type);
            ?>
            <tr>
                  <td><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $movie_id ?>&movie_name=<?php echo $movie_name ?>"><?php echo $movie_name ?></a></td>
                  <td><?php echo $movie_year ?></td>
                  <td><?php echo $director['people_fullname'] ?></td>
                  <td><?php echo $leadactor['people_fullname'] ?></td>
                  <td><?php echo $movietype['movietype_label'] ?></td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
    endif;   
    ?>
    <br/>
    <a href="<?php echo URL_SITE; ?>index.php">Volver al menú principal</a>

</body>

</html>