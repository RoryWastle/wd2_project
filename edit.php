<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 2, 2021
     * Purpose: To edit or delete a new album in the database.
     **********************************************************************/
    
    session_start();

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
        $statement = $db->prepare($query);
        $statement->execute();
        $genres = $statement->fetchAll();

        $query = "SELECT * FROM albumgenre WHERE albumID = :albumID";
        $statement = $db->prepare($query);
        $statement->bindValue(':albumID', $albumID, PDO::PARAM_INT);
        $statement->execute();
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
    <title>Winnipeg's Classic Albums | <?= $album['title'] ?> Edit</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.tiny.cloud/1/22f8urgbl5k7uvnn13y50g56d8zw7wmahw6z53ecoj1ei9rk/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">

        <h1 class="pt-3">Edit Album</h1>

        <form action="process_post.php?albumID=<?= $albumID ?>" method="post" enctype="multipart/form-data" class="form-inline">
            <div class="form-group p-3">
                <label for="title">Title*</label>
                <input class="form-control" name="title" id="title" value="<?= $album['title'] ?>" />
            </div>
            <div class="form-group p-3">
                <label for="artist">Artist*</label>
                <input class="form-control" name="artist" id="artist" value="<?= $album['artist'] ?>" />
            </div>
            <?php if($album['coverURL'] == NULL): ?>
                <div class="form-group p-3">
                    <label for="image">Album Cover</label>
                    <input class="form-control" type="file" name="image" id="image">
                </div>
            <?php else: ?>
                <div class="form-group p-3">
                    <input  
                        class="btn btn-danger" 
                        type="submit" 
                        name="command" 
                        value="Remove Image" 
                        onclick="return confirm('Are you sure you wish to remove this image?')"
                    />
                </div>
            <?php endif ?>
            <div class="form-group p-3">
                <label for="year">Year</label>
                <input class="form-control mb-2" name="year" id="year" value="<?= $album['year'] ?>" />
            </div>
            <div class="card genre-card">
                <div class="card-body p-3">
                    <label for="genre1">Genre 1</label>
                    <select class="form-control" id="genre1" name="genre1">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option 
                                value=<?= $genre['genreID'] ?> 
                                <?php if(count($currentGenres) > 0 && $genre['genreID'] == $currentGenres[0]['genreID']): ?> selected <?php endif ?> >
                                <?= $genre['genre'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <label for="genre2">Genre 2</label>
                    <select class="form-control" id="genre2" name="genre2">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option 
                                value=<?= $genre['genreID'] ?> 
                                <?php if(count($currentGenres) > 1 && $genre['genreID'] == $currentGenres[1]['genreID']): ?> selected <?php endif ?> >
                                <?= $genre['genre'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <label for="genre3">Genre 3</label>
                    <select class="form-control" id="genre3" name="genre3">
                        <option value="0"></option>
                        <?php foreach ($genres as $genre): ?>
                            <option 
                                value=<?= $genre['genreID'] ?> 
                                <?php if(count($currentGenres) > 2 && $genre['genreID'] == $currentGenres[2]['genreID']): ?> selected <?php endif ?> >
                                <?= $genre['genre'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group p-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="15"><?= $album['description'] ?></textarea>
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Update" />
                <input class="btn btn-danger" type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
            </div>
        </form>
    </div>
        
    <?php include('footer.php'); ?>
</body>
</html>

