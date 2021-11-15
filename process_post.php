<?php
	/**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 2 2021
     * Purpose: To create a new record in the classic albums database.
     **********************************************************************/

    //  Gets the user posting the album.
    function getUser($db){
        $query = "SELECT name FROM users WHERE userID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(":id", $_SESSION['user'], PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();
        return $row['name'];
    }

    //  Gets the most recent album added.
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

    //  Remove previous linked genres to albums when updated.
    function remove_previous_genres($albumID, $db){
        $query = "DELETE FROM albumgenre WHERE albumID = :albumID";
        $statement = $db->prepare($query);
        $statement->bindValue(":albumID", $albumID, PDO::PARAM_INT);
        $statement->execute();
    }

    //  Create the path to where files shall be uploaded to.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads'){
        $current_folder = dirname(__FILE__);
        $path_segments  = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
        return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    //  Test to see if uploaded files are images.
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = mime_content_type($temporary_path);
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }

    //  Upload three sizes of the same image to the uploads folder.
    function upload_files($temporary_path, $new_path, $image_filename) {
        $image = new \Gumlet\ImageResize($temporary_path);
        $image->resizeToWidth(75);
        $image->save(file_upload_path('thumbnail_' . $image_filename));

        $image = new \Gumlet\ImageResize($temporary_path);
        $image->resizeToWidth(400);
        $image->save(file_upload_path('medium_' . $image_filename));

        move_uploaded_file($temporary_path, $new_path);
    }

    //  Delete the three images from the file system.
    function delete_files($image) {
        unlink("uploads/".$image);
        unlink("uploads/medium_".$image);
        unlink("uploads/thumbnail_".$image);
    }

    session_start();

    //  Required files.
    require('db_connect.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResize.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResizeException.php');

    $valid = false;
    
    //  If the command was to remove the image.
    if ($_POST && $_POST['command'] == "Remove Image"){
        //  Select the cover url.
        $query = "SELECT coverURL FROM albums WHERE albumID = :albumID";
        $statement = $db->prepare($query);
        $statement->bindValue(":albumID", $_GET['albumID'], PDO::PARAM_INT);
        $statement->execute();
        $image = $statement->fetch()['coverURL'];

        //  Remove the three imaged associated with the url.
        delete_files($image);

        //  Set the cover url value in for this album to null.
        $query = "UPDATE albums SET coverURL = NULL, updated = current_timestamp() WHERE albumID = :albumID";
        $statement = $db->prepare($query);
        $statement->bindValue(":albumID", $_GET['albumID'], PDO::PARAM_INT);

        //  Execute the command.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            $valid = true;

            header("Location: edit.php?albumID=".$_GET['albumID']);
            exit;
        }
    }
    //  If a different command was made.
    elseif ($_POST && !empty($_POST['title']) && !empty($_POST['artist'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title  = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //  Sanitize the year if one was entered.
        $year = !empty($_POST['year']) ? filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT) : NULL;

        //  If the year is not numeric or is not four digits, set the value to null.
        if(is_numeric($year)){
            if($year < 1000 && $year >= 9999){
                $year = NULL;
            }
        }
        else{
            $year = NULL;
        }

        //  Sanitize the description if one was entered. Allow certain tags because of the WYSIWYG.
        $allowedTags = '<p><blockquote><div><pre><strong><em><ol><ul><li><code><h1><h2><h3><h4><h5><h6><span><del><sup><sub>';
        $description = !empty($_POST['description']) ? strip_tags(stripslashes($_POST['description']), $allowedTags) : NULL;

        //  See if an image upload is detected.
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
        
        //  If the command was Create.
        if($_POST['command'] == "Create"){
            //  Build the query based on if an image was uploaded.
            if($image_upload_detected) { 
                //  Build the parameterized SQL query and bind to the above sanitized values.
                $query = "INSERT INTO albums (title, artist, year, coverURL, description, postedBy) VALUES (:title, :artist, :year, :coverURL, :description, :postedBy)";
            }
            else{
                $query = "INSERT INTO albums (title, artist, year, description, postedBy) VALUES (:title, :artist, :year, :description, :postedBy)";
            }
        }
        //  If the command was to Update
        elseif($_POST['command'] == "Update"){
            //  Build the query based on if an image was uploaded.
            if($image_upload_detected) { 
                //  Build the parameterized SQL query and bind to the above sanitized values.
                $query = "UPDATE albums SET title = :title, artist = :artist, year = :year, coverURL = :coverURL, description = :description, postedBy = :postedBy, updated = current_timestamp() WHERE albumID = :albumID";
            }
            else{
                $query = "UPDATE albums SET title = :title, artist = :artist, year = :year, description = :description, postedBy = :postedBy, updated = current_timestamp() WHERE albumID = :albumID";
            }

            //  Remove any genres associated with this album.
            remove_previous_genres($_GET['albumID'], $db);
        }
        //  If the command was to delete.
        else{
            //  Remove the associated images from the file system.
            $query = "SELECT coverURL FROM albums WHERE albumID = :albumID";
            $statement = $db->prepare($query);
            $statement->bindValue(":albumID", $_GET['albumID'], PDO::PARAM_INT);
            $statement->execute();
            $image = $statement->fetch()['coverURL'];

            delete_files($image);

            //  Build the Delete query.
            $query = "DELETE FROM albums WHERE albumID = :albumID";
        }

        $statement = $db->prepare($query);

        //  If the command was not to Delete.
        if($_POST['command'] != "Delete"){
            //  Bind values to the parameters
            $statement->bindValue(":title", $title);
            $statement->bindValue(":artist", $artist);
            $statement->bindValue(":year", $year, PDO::PARAM_INT);
            $statement->bindValue(":description", $description);
            $statement->bindValue(":postedBy", $_SESSION['user']);

            //  If an image was uploaded.
            if($image_upload_detected) { 
                $image_filename       = $_SESSION['user'] . '-' . time() . '-' . $_FILES['image']['name'];
                $temporary_image_path = $_FILES['image']['tmp_name'];
                $new_image_path       = file_upload_path($image_filename);

                //  If this file was an image, upload it and bind the value to the parameter.
                if(file_is_an_image($temporary_image_path, $new_image_path)) {
                    upload_files($temporary_image_path, $new_image_path, $image_filename);
                    $statement->bindValue(":coverURL", $image_filename);
                }
                //  Otherwise bind NULL to the parameter.
                else{
                    $statement->bindValue(":coverURL", NULL);
                }
            }
        }

        //  If the command was not to Create, bind the GET album id to the parameter.
        if($_POST['command'] != "Create"){
            $statement->bindValue(":albumID", $_GET['albumID'], PDO::PARAM_INT);
        }

        //  Execute the command.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            $valid = true;
            
            //  If the command was to create, associate genres with the recently added album.
            if($_POST['command'] == "Create"){
                $albumID =  getMostRecent($db);
                addGenres($albumID, $db);
            }
            //  If the command was to Update, associate genres with the album from the GET album id.
            elseif($_POST['command'] == "Update"){
                addGenres($_GET['albumID'], $db);
            }

            header("Location: show.php?albumID=".$_GET['albumID']);
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