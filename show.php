<?php
    /**********************************************************************
     * Author:  Rory Wastle
     * Date:    November 3, 2021
     * Purpose: To show a certain album in the database.
     **********************************************************************/
    
    session_start();

    require('db_connect.php');

    //  If an album parameter was provided.
    if(isset($_GET['album'])){
        $id = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_NUMBER_INT);

        //  If there was a post and the comment input is not blank.
        if ($_POST && $_POST['comment'] != NULL) {
            //  Sanitize the new comment.
            $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //  Insert the new comment into the table.
            $query = "INSERT INTO comments (comment, albumID, userID) VALUES (:comment, :albumID, :userID)";
            $statement = $db->prepare($query);
            $statement->bindValue(':comment', $comment);
            $statement->bindValue(':albumID', $id, PDO::PARAM_INT);
            $statement->bindValue(':userID', $_SESSION['user'], PDO::PARAM_INT);
            
            //  If the statement was executed, go back to the page.
            if ($statement->execute()) {
                header("Location: show.php?album={$_GET['album']}");
                exit;
            }
        }
        //  If there was a post and no comment was entered.
        elseif ($_POST) {
            header("Location: show.php?album={$_GET['album']}");
            exit;
        }

        //  If a delete request for a certain comment was made.
        if (isset($_GET['toDelete'])) {
            //  Delete the requested comment from the table.
            $query = "DELETE FROM comments WHERE commentID = :commentID";
            $statement = $db->prepare($query);
            $statement->bindvalue(':commentID', $_GET['toDelete']);

            //  If the statement was executed, go back to the page.
            if ($statement->execute()) {
                header("Location: show.php?album={$_GET['album']}");
                exit;
            }
        }

        //  Select the album.
        $query = "SELECT * FROM albums WHERE albumID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $album = $statement->fetch();

        //  Select the name of the user that last updated this album.
        $query = "SELECT name FROM users WHERE userID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $album['postedBy'], PDO::PARAM_INT);
        $statement->execute();

        $poster = $statement->fetch();

        //  Select the genres associated with this album.
        $query = "SELECT g.genre FROM genres g JOIN albumgenre a ON g.genreID = a.genreID WHERE a.albumID = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $genres = $statement->fetchAll();

        //  Select the comments associated with this album.
        $query = "SELECT c.commentID, c.comment, c.userID, u.name FROM comments c JOIN users u ON u.userID = c.userID WHERE albumID = :albumID ORDER BY commentID DESC";
        $statement = $db->prepare($query);
        $statement->bindValue(':albumID', $id, PDO::PARAM_INT);
        $statement->execute();

        $comments = $statement->fetchAll();

        //  Assume the current user is not an admin.
        $admin = false;

        //  If the current user is logged in.
        if (isset($_SESSION['user'])) {
            //  Select if this user is an admin.
            $query = "SELECT admin FROM users WHERE userID = :userID";
            $statement = $db->prepare($query);
            $statement->bindValue(':userID', $_SESSION['user'], PDO::PARAM_INT);
            $statement->execute();

            $admin = $statement->fetch()['admin'];
        }
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
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container float-container">
        <h1 class="pt-3"><?= $album['title'] ?></h1>

        <div class="card" id="album-summary">
            <div class="card-body">
                <h4 class="card-title">
                    <?= $album['title'] ?>
                        <?php if (isset($_SESSION['user'])): ?>
                        <small><a href="edit.php?albumID=<?= $id ?>">[Edit]</a></small>
                    <?php endif ?>   
                </h4>
                
                <h6 class="card-subtitle mb-2 text-muted"><?= $album['artist'] ?> - <?= $album['year'] == NULL ? "[unknown year]" : $album['year'] ?></h6>
                <?php if($album['coverURL'] != NULL): ?>
                    <img src="uploads/medium_<?= $album['coverURL'] ?>" alt="<?= $album['title'] ?> cover.">
                <?php endif ?>
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
                <?php if(isset($_SESSION['user'])): ?>
                    <button class="btn btn-primary">Like</button>
                <?php endif ?>
            </div>
        </div>
        <div class="p-3">
            <p><?= $album['description'] ?></p>
        </div>
        
        <div id="end-of-summary">
            <p>Last updated by <?= $poster['name'] == NULL ? "[unknown]" : $poster['name'] ?> on <?= $album['updated'] ?></p>
        </div>

        <hr />

        <div class="p-3">
            <h3>Comments</h3>

            <?php if(isset($_SESSION['user'])): ?>
                <form action="show.php?album=<?= $_GET['album'] ?>" method="post">
                    <label for="comment"></label>
                    <textarea class="form-control" name="comment" id="comment"></textarea>
                    <input type="submit" name="command" value="Post" class="btn btn-primary">
                </form>
                <br />
            <?php endif ?>

            <?php foreach ($comments as $comment): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?= $comment['name'] ?></a></h4>
                        <p><?= $comment['comment'] ?></p>
                        <?php if($admin || (isset($_SESSION['user']) && $comment['userID'] == $_SESSION['user'])): ?>
                            <p><a href="?album=<?= $_GET['album'] ?>&toDelete=<?= $comment['commentID'] ?>">Delete Comment</a></p>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>