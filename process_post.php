<?php
	/**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 2 2021
     * Purpose: To create a new record in the classic albums database.
     **********************************************************************/

    function getMostRecent($db){
        $query = "SELECT MAX(albumID) AS latest FROM albums";
        $statement = $db->prepare($query);
        $statement->execute();
        $row = $statement->fetch();
        return $row['latest'];
    }

    //  Add genres to the album/genre table if values were entered.
    function addGenres($albumID, $db){
        $genres = [$_POST['genre1'], $_POST['genre2'], $_POST['genre3']];
        foreach ($genres as $genre) {
            if($genre != 0){
                $query = "INSERT INTO albumgenre (albumID, genreID) VALUES (:albumID, :genreID)";
                $statement = $db->prepare($query);
                $statement->bindValue(":albumID", $albumID, PDO::PARAM_INT);
                $statement->bindValue(":genreID", $genre, PDO::PARAM_INT);
                $statement->execute();
            }
        }
    }


    require('db_connect.php');

    $valid = false;
    
    if ($_POST && !empty($_POST['title']) && !empty($_POST['artist'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title  = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $year = !empty($_POST['year']) ? filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT) : NULL;
        if(is_numeric($year)){
            if($year < 1000 && $year >= 9999){
                $year = NULL;
            }
        }
        else{
            $year = NULL;
        }

        $description = !empty($_POST['description']) ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : NULL;
        
        if($_POST['command'] == "Create"){
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "INSERT INTO albums (title, artist, year, coverURL, description, postedBy) VALUES (:title, :artist, :year, :coverURL, :description, :postedBy)";
        }
        elseif($_POST['command'] == "Update"){
            //  Build the parameterized SQL query and bind to the above sanitized values.
            $query = "UPDATE albums SET title = :title, artist = :artist, year = :year, coverURL = :coverURL, description = :description, updated = :updated WHERE albumID = :albumID";
        }
        else{
            $query = "DELETE FROM albums WHERE albumID = :albumID";
        }

        $statement = $db->prepare($query);

        if($_POST['command'] != "Delete"){
            //  Bind values to the parameters
            $statement->bindValue(":title", $title);
            $statement->bindValue(":artist", $artist);
            $statement->bindValue(":year", $year, PDO::PARAM_INT);
            $statement->bindValue(":coverURL", NULL); // CHANGE LATER
            $statement->bindValue(":description", $description);
        }  
        if($_POST['command'] != "Create"){
            $statement->bindValue(":albumID", 1/*$_GET['albumID']*/, PDO::PARAM_INT); // CHANGE LAATER
        }

        if($_POST['command'] == "Create"){
            $statement->bindValue(":postedBy", 1); // CHANGE LATER
        }
        elseif($_POST['command'] == "Update"){
            $statement->bindValue(":updated", "current_timestamp()");
        }

        //  Execute the command.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            $valid = true;
            
            if($_POST['command'] == "Create"){
                $albumID =  getMostRecent($db);
                addGenres($albumID, $db);
            }

            header("Location: index.php");
            exit;
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Winnipeg's Classic Albums | Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <?php if(!$valid): ?>
        <div id="wrapper">
            <h2>An error occurred when adding your album.</h2>
            <p>Both the title and artist must be at least one character.</p>
            <p><a href="index.php">Return Home</a></p>
        </div> 
    <?php endif ?>
</body>
</html>