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

        .odd{
            background-color: #ccc;
        }
        
        .even{
            background-color: white;
        }
    </style>
</head>

<body>
    <!-- CONNEXION BBDD -->
    <?php require('connect.php'); ?>

    <?php
    //Check $_GETs
    if(isset($_GET['movie_id']) && isset($_GET['movie_name'])  ):
        ?>
        <h1><?php echo $_GET['movie_name'] ?></h1>
        <?php
        //Query select 
        $query_information = 'SELECT movie_name, movie_year, movie_director, movie_leadactor, movie_type, movie_running_time, movie_cost, movie_takings FROM movie WHERE movie_id = ' . $_GET['movie_id'];
        //Call this function to extract data
        $data = extractData($query_information);
        $row = $data->fetch_assoc();

        //Query AVG Ranking and extract final Ranking
        $query_ranking = 'SELECT ROUND(avg( review_rating), 0) as RANKING FROM reviews WHERE review_movie_id = ' . $_GET['movie_id'];
        $ranking = extractData($query_ranking);
        $final_ranking = $ranking->fetch_assoc();
        // var_dump($final_ranking['RANKING']);

        //Save data into vars
        //var_dump($row);
        $movie_name         = $row['movie_name'];
        $movie_director     = get_director($row['movie_director']);
        $movie_leadactor    = get_leadactor($row['movie_leadactor']);
        $movie_year         = $row['movie_year'];
        $movie_running_time = $row['movie_running_time'] .' mins';
        $movie_takings      = $row['movie_takings'] . ' million';
        $movie_cost         = $row['movie_cost'] . ' million';
        $movie_health       = calculate_differences($row['movie_takings'],$row['movie_cost']);
        $movie_ranking      = generate_ratings($final_ranking['RANKING']);


        //Print table Details
        ?>
        <h3><em>Details</em></h3>
        <table cellpadding="2" cellspacing="2" style="width: 70%; margin-left: auto; margin-right: auto;">
            <tr>
                <td><strong>Title</strong></strong></td>
                <td><?php echo $movie_name ?></td>
                <td><strong>Release Year</strong></strong></td>
                <td><?php echo $movie_year ?> </td>
            </tr>
            <tr>
                <td><strong>Movie Director</strong></td>
                <td><?php echo $movie_director['people_fullname'] ?></td>
                <td><strong>Cost</strong></td>
                <td><?php echo $movie_cost ?></td>
            </tr>
            <tr>
                <td><strong>Lead Actor</strong></td>
                <td><?php echo $movie_leadactor['people_fullname'] ?></td>
                <td><strong>Takings</strong></td>
                <td><?php echo $movie_takings ?></td>
            </tr>
            <tr>
                <td><strong>Running Time</strong></td>
                <td><?php echo $movie_running_time ?></td>
                <td><strong>Health</strong></td>
                <td><?php echo $movie_health ?></td>
            </tr>
        </table>
        <br/>
        <table cellpadding="2" cellspacing="2" style="width: 70%; margin-left: auto; margin-right: auto;">
            <thead><th>RANKING</th></thead>
            <tbody style="text-align: center;"><td><?php echo $movie_ranking ?></td></tbody>
        </table>
        <?php

        //REVIEWS
        $query_reviews = 'SELECT review_movie_id, review_date, reviewer_name, review_comment, review_rating FROM reviews WHERE review_movie_id = ' . $_GET['movie_id'] . ' ORDER BY review_date DESC';
        //Call this function to extract data
        $data = extractData($query_reviews);
        ?>
        <h3><em>Reviews</em></h3>
        <!-- Check GETs var -->
            <?php
                if(isset($_GET['filter']) && isset($_GET['order'])):
                    //Save data
                    $order = $_GET['order'];
                    $filter = $_GET['filter'];
                    
                    if($filter == 'review_date' || $filter == 'reviewer_name' || $filter == 'review_comment' || $filter == 'review_rating'):                        
                        //Switch order selected 
                        switch ($order):
                            case 'asc':
                                ?>
                                <table cellpadding="2" cellspacing="2" style="width: 90%; margin-left: auto; margin-right: auto;">
                                    <thead>
                                        <tr>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_date&order=desc">DATE</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=reviewer_name&order=desc">REVIEWER</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_comment&order=desc">COMMENTS</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_rating&order=desc">RATING</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //Print TBODY order
                                            printTableReview($filter, $order);
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                break;
                            case 'desc':
                                ?>
                                <table cellpadding="2" cellspacing="2" style="width: 90%; margin-left: auto; margin-right: auto;">
                                    <thead>
                                        <tr>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_date&order=asc">DATE</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=reviewer_name&order=asc">REVIEWER</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_comment&order=asc">COMMENTS</a></th>
                                            <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_rating&order=asc">RATING</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //Print TBODY order
                                            printTableReview($filter, $order);
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                break;
                            default:
                                ?>
                                <h3>El orden establecido no existe...</h3>
                                <?php
                                
                        endswitch;                
                    else:
                        ?>
                        <h3>El filtro establecido no existe...</h3>
                        <?php
                    endif;
                    
                else:
                // Always show results no filter no order
                    ?>
                    <table cellpadding="2" cellspacing="2" style="width: 90%; margin-left: auto; margin-right: auto;">
                        <thead>
                            <tr>
                                <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_date&order=asc">DATE</a></th>
                                <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=reviewer_name&order=asc">REVIEWER</a></th>
                                <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_comment&order=asc">COMMENTS</a></th>
                                <th><a href="<?php echo URL_SITE; ?>details.php?movie_id=<?php echo $_GET['movie_id'] ?>&movie_name=<?php echo $movie_name ?>&filter=review_rating&order=asc">RATING</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            while( $row_reviews = $data->fetch_assoc()):
                                extract($row_reviews);
                                //var_dump($row_reviews);
                                ?>
                                <tr>
                                <td class="<?php echo ($i % 2 == 0) ? 'odd' : 'even';?>" style="vertical-align:top; text-align: center;"><?php echo $review_date ?></td>
                                <td class="<?php echo ($i % 2 == 0) ? 'odd' : 'even';?>" style="vertical-align:top;"><?php echo $reviewer_name ?></td>
                                <td class="<?php echo ($i % 2 == 0) ? 'odd' : 'even';?>" style="vertical-align:top;"><?php echo $review_comment ?></td>
                                <td class="<?php echo ($i % 2 == 0) ? 'odd' : 'even';?>" style="vertical-align:top;"><?php echo generate_ratings($review_rating) ?></td>
                                </tr>
                                <?php
                            $i++;
                            endwhile;                       
                            ?>     
                        </tbody>
                    </table>
                    <?php
                endif;
    else:
        ?>
        <h2>No has indicado ninguna película...</h2>
        <?php
        @$bbdd->close;
    endif;
    ?>
    <br/>
    <a href="<?php echo URL_SITE; ?>index.php">Volver al menú principal</a>
</body>
</html>


