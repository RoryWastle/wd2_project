<?php
    /**********************************************************************
     * Author: Rory Wastle
     * Date: September 17, 2021
     * Purpose: To show a certain blog post in the database.
     **********************************************************************/
    require('db_connect.php');

    if(isset($_GET['id'])){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        //  Select the quote to edit/delete.
        $query = "SELECT * FROM blogs WHERE id = :id";

        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $blog = $statement->fetch();
    } else {
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blogs - <?= $blog['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div id="wrapper">
        <h1>Blogs - <?= $blog['title'] ?></h1>

        <ul id="nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="create.php">New Post</a></li>
        </ul>

        <div id="posts">
            <h2><?= $blog['title'] ?></h2>
            <p>
                <small><?= date('F j, Y, g:i a', strtotime($blog['timestamp'])) ?> - <a href="edit.php?id=<?= $blog['id'] ?>">edit</a></small>
            </p>
            <div class="content">
                <?= $blog['content'] ?>
            </div>
        </div>
        <div>
            Rory Wastle, 2021
        </div>
    </div>
</body>
</html>