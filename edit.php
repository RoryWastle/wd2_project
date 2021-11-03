<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 2, 2021
     * Purpose: To edit or delete a new album in the database.
     **********************************************************************/

    require('db_connect.php');
    //require('authenticate.php');

    if(isset($_GET['albumID'])){
        $albumID = filter_input(INPUT_GET, 'albumID', FILTER_SANITIZE_NUMBER_INT);

        //  Select the album to edit/delete.
        $query = "SELECT * FROM albums WHERE albumID = :albumID";
        $statement = $db->prepare($query);
        $statement->bindValue(':albumID', $albumID, PDO::PARAM_INT);
        $statement->execute();

        $album = $statement->fetch();

        $query = "SELECT * FROM genres";
        $statement = $db->prepare($query); // Returns a PDOStatement object.
        $statement->execute(); // The query is now executed.
        $genres = $statement->fetchAll();

        $query = "SELECT * FROM albumgenre WHERE albumID = $_GET['albumID']";
        $statement = $db->prepare($query); // Returns a PDOStatement object.
        $statement->execute(); // The query is now executed.
        $currentGenres = $statement->fetchAll();
    } else {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Winnipeg's Classic Albums | Edit Albums</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">

        <h1 class="pt-3">Edit Album</h1>

        <form action="process_post.php" method="post" class="form-inline">
            <div class="form-group p-3">
                <label for="title">Title*</label>
                <input class="form-control" name="title" id="title" value=<?= $album['title'] ?>/>
            </div>
            <div class="form-group p-3">
                <label for="artist">Artist*</label>
                <input class="form-control" name="artist" id="artist" value=<?= $album['artist'] ?>/>
            </div>
            <div class="form-group p-3">
                <label for="image">Album Cover</label>
                <input class="form-control" type="file" name="image" id="image"> // CHANGE LATER
            </div>
            <div class="form-group p-3">
                <label for="year">Year</label>
                <input class="form-control mb-2" name="year" id="year" value=<?= $album['year'] ?>/>
            </div>
            <div class="card genre-card">
                <div class="card-body p-3">
                    <label for="genre1">Genre 1</label>
                    <select class="form-control" id="genre1" name="genre1">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option value=<?= $genre['genreID'] ?>><?= $genre['genre'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <label for="genre2">Genre 2</label>
                    <select class="form-control" id="genre2" name="genre2">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option value=<?= $genre['genreID'] ?>><?= $genre['genre'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <label for="genre3">Genre 3</label>
                    <select class="form-control" id="genre3" name="genre3">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option value=<?= $genre['genreID'] ?>><?= $genre['genre'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group p-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"><?= $album['description'] ?></textarea>
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Update" />
                <input class="btn btn-warning" type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
            </div>
        </form>
    </div>
        
    <?php include('footer.php'); ?>
</body>
</html>

