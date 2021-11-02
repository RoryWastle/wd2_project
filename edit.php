<?php
    /**********************************************************************
     * Author: Rory Wastle
     * Date: September 17, 2021
     * Purpose: To edit or delete a certain blog post in the database.
     **********************************************************************/
    require('db_connect.php');
    require('authenticate.php');

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
    <title>Blogs - Edit Post</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div id="wrapper">
        <h1>Blogs - Edit Post</h1>

        <ul id="nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="create.php">New Post</a></li>
        </ul>
        <form action="process_post.php?id=<?= $blog['id'] ?>" method="post">
            <fieldset>
                <legend>Edit Blog Post</legend>
                <p>
                    <label for="title">Title</label>
                    <input name="title" id="title" value="<?= $blog['title'] ?>" />
                </p>
                <p>
                    <label for="content">Content</label>
                    <textarea name="content" id="content"><?= $blog['content'] ?></textarea>
                </p>
                <p>
                    <input type="submit" name="command" value="Update" />
                    <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
                </p>
            </fieldset>
        </form>
        <div>
            Rory Wastle, 2021
        </div>
    </div>
</body>
</html>

