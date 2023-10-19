<?php
    //Error's quote
    $error = 'Error de Connexión número (' . $bbdd->connect_errno . ') ' . $bbdd->connect_error;
    
    //This function execute a query
   function executeQuery($query){
       //Call vars
       global $bbdd;
       global $error;

       //Execute query
       $data = @$bbdd->query($query) or die($error);
   }
   //This function extract Data
   function extractData($query){

        //Call vars
       global $bbdd;
       global $error;

       //Execute query and reset
       $data = @$bbdd->query($query) or die($error);
       $data->data_seek(0);

       //Return data
       return $data;
   }

    // take in the id of a director and return his/her full name
    function get_director($director_id){
        global $bbdd;
        global $error;

        $query = 'SELECT people_fullname FROM people WHERE people_id = ' . $director_id;
        $data = @$bbdd->query($query) or die($error);
        $result = $data->fetch_assoc();
        return $result;
    }

    // take in the id of a lead actor and return his/her full name
    function get_leadactor($leadactor_id){
        global $bbdd;
        global $error;

        $query = 'SELECT people_fullname FROM people WHERE people_id = ' . $leadactor_id;
        $data = @$bbdd->query($query) or die($error);
        $result = $data->fetch_assoc();
        return $result;
    }

    // take in the id of a movie type and return the meaningful textual
    function get_movietype($type_id){

        global $bbdd;
        global $error;

        $query = 'SELECT movietype_label FROM movietype WHERE movietype_id = ' . $type_id;
        $data = @$bbdd->query($query) or die($error);
        $result = $data->fetch_assoc();
        return $result;
    }

    // function to generate ratings
    function generate_ratings($rating) {
        $movie_rating = '';
        for ($i = 0; $i < $rating; $i++) {
            $movie_rating .= '<img src="assets/img/estrella.png" width=30px alt="Estrella" title="Estrella"/>';
        }
        return $movie_rating;
    }

    
    // function to calculate if a movie made a profit, loss or just broke even
    function calculate_differences($takings, $cost) {
        $difference = $takings - $cost;
        if ($difference < 0) {     
            $color = 'red';
            $difference = '$' . abs($difference) . ' million';
        } elseif ($difference > 0) {
            $color ='green';
            $difference = '$' . $difference . ' million';
        } else {
            $color = 'blue';
            $difference = 'broke even';
        }
        return '<span style="color:' . $color . ';">' . $difference . '</span>';
    }


    //Print table review
    function printTableReview($filter, $order){
        //Final filter query
        $query_order = 'SELECT  review_date, reviewer_name, review_comment, review_rating FROM reviews WHERE review_movie_id = ' . $_GET['movie_id'] . ' ORDER BY ' . $filter . ' ' . $order;
        // echo $query_order;
        $data = extractData($query_order); 
                $i = 0;
                while( $row = $data->fetch_assoc()):
                    extract($row);
                    //var_dump($row);
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
    }




?>