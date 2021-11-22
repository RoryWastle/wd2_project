<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 18 2021
     * Purpose: View, add, delete genres in the ablums database.
     **********************************************************************/
    
    session_start();

    require('db_connect.php');

    //  If there was a post and the new genre input is not blank/
    if ($_POST && $_POST['newgenre'] != NULL) {
        //  Sanitize the new genre.
        $newgenre = filter_input(INPUT_POST, 'newgenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //  Insert the new genre into the table.//  If the statement was executed, go back to the page.
        $query = "INSERT INTO genres (genre) VALUES (:newgenre)";
        $statement = $db->prepare($query);
        $statement->bindvalue(':newgenre', $newgenre);
        
        //  If the statement was executed, go back to the page.
        if ($statement->execute()) {
            header("Location: genres.php");
            exit;
        }
    }
    //  If there was a post and no new genre was entered.
    elseif ($_POST) {
        header("Location: genres.php");
        exit;
    }

    //  If a delete request for a certain album was made.
    if (isset($_GET['toDelete'])) {
        //  Delete the requested genre from the table.
        $query = "DELETE FROM genres WHERE genreID = :genreID";
        $statement = $db->prepare($query);
        $statement->bindvalue(':genreID', $_GET['toDelete']);

        if ($statement->execute()) {
            header("Location: genres.php");
            exit;
        }
    }

    $query = "SELECT * FROM genres ORDER BY genre";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $genres = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Winnipeg's Classic Albums | Genres</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">

        <h1 class="pt-3">Genres</h1>

        <table class="table table-hover w-50">
            <?php foreach($genres as $genre): ?>
                <tr>
                    <td><?= $genre['genre'] ?></td>
                    <td><a href="?toDelete=<?= $genre['genreID'] ?>">Delete</a></td>
                </tr>
            <?php endforeach ?>
        </table>

        <h3>Add Genre</h3>

        <form class="input-group" action="genres.php" method="post">
            <input class="form-control" name="newgenre" id="newgenre">
            <input class="btn btn-primary" type="submit" name="command" value="Add">
        </form>

    </div>
        
    <?php include('footer.php'); ?>
</body>
</html>