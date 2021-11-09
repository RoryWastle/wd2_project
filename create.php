<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 2, 2021
     * Purpose: To create a new album in the database.
     **********************************************************************/

    require('db_connect.php');
    //require('authenticate.php');

    $query = "SELECT * FROM genres";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $genres = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Winnipeg's Classic Albums | New Album</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">

        <h1 class="pt-3">New Album</h1>

        <form action="process_post.php" method="post" enctype="multipart/form-data" class="form-inline">
            <div class="form-group p-3">
                <label for="title">Title*</label>
                <input class="form-control" name="title" id="title" />
            </div>
            <div class="form-group p-3">
                <label for="artist">Artist*</label>
                <input class="form-control" name="artist" id="artist" />
            </div>
            <div class="form-group p-3">
                <label for="image">Album Cover</label>
                <input class="form-control" type="file" name="image" id="image">
            </div>
            <div class="form-group p-3">
                <label for="year">Year</label>
                <input class="form-control mb-2" name="year" id="year" />
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
                <textarea class="form-control" name="description" id="description"></textarea>
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Create" />
            </div>
        </form>
    </div>
        
    <?php include('footer.php'); ?>
</body>
</html>