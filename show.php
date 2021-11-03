<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 3, 2021
     * Purpose: To show a certain album in the database.
     **********************************************************************/
    require('db_connect.php');

    if(isset($_GET['album'])){
        $id = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_NUMBER_INT);

        //  Select the quote to edit/delete.
        $query = "SELECT * FROM albums WHERE albumID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $album = $statement->fetch();

        $query = "SELECT name FROM users WHERE userID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $album['postedBy'], PDO::PARAM_INT);
        $statement->execute();

        $user = $statement->fetch();

        $query = "SELECT g.genre FROM genres g JOIN albumgenre a ON g.genreID = a.genreID WHERE a.albumID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $genres = $statement->fetchAll();
    } else {
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Winnipeg's Classic Ablums | <?= $album['title'] ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesa.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container float-container">
        <h1 class="pt-3"><?= $album['title'] ?></h1>

        <div class="card" id="album-summary">
            <div class="card-body">
                <h4 class="card-title"><?= $album['title'] ?></h4>
                <small><a href="edit.php?<?= $id ?>">Edit</a></small>
                <h6 class="card-subtitle mb-2 text-muted"><?= $album['artist'] ?> - <?= $album['year'] == NULL ? "[unknown year]" : $album['year'] ?></h6>
                <img src="uploads/medium_<?= $album['coverURL'] ?>" alt="<?= $album['title'] ?> cover" />
                <hr />
                <?php if (count($genres) > 0): ?>
                    <h6 class="card-subtitle mb-2">Genres:</h6>
                    <ul>
                        <?php foreach ($genres as $genre): ?>
                            <li><?= $genre['genre'] ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
                <p><?= $album['likes'] ?> people like this album</p>
                <button class="btn btn-success">Like</button>
            </div>
        </div>

        <p class="p-3 pl-3"><?= $album['description'] ?></p>
        
        <div id="end-of-summary">
            <p>Posted by <?= $user['name'] ?>. Last updated <?= $album['updated'] ?></p>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>