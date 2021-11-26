<?php
/**********************************************************************
 * Author:  Rory Wastle
 * Date:    November 22 2021
 * Purpose: View, promote, demote, delete users in the ablums database.
 **********************************************************************/
    
    session_start();

    require('db_connect.php');

    //  If a delete request for a certain user was made.
    if (isset($_GET['toDelete'])) {
        //  Delete the requested user from the table.
        $query = "DELETE FROM users WHERE userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(":userID", $_GET['toDelete'], PDO::PARAM_INT);

        if ($statement->execute()) {
            header("Location: users.php");
            exit;
        }
    }

    //  If a promote request for a certain user was made.
    if (isset($_GET['promote'])) {
        //  Set the requested user's admin value to true.
        $query = "UPDATE users SET admin = TRUE WHERE userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(":userID", $_GET['promote'], PDO::PARAM_INT);

        if ($statement->execute()) {
            header("Location: users.php");
            exit;
        }
    }

    //  If a demote request for a certain user was made.
    if (isset($_GET['demote'])) {
        //  Set the requested user's admin value to false.
        $query = "UPDATE users SET admin = FALSE WHERE userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(":userID", $_GET['demote'], PDO::PARAM_INT);

        if ($statement->execute()) {
            header("Location: users.php");
            exit;
        }
    }

    $query = "SELECT * FROM users ORDER BY admin DESC, name";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $users = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Winnipeg's Classic Albums | Users</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">

        <h1 class="pt-3">Users</h1>

        <table class="table table-hover w-50">
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['name'] ?></td>
                    <?php if($user['userID'] != $_SESSION['user']): ?>
                        <td><a href="?toDelete=<?= $user['userID'] ?>">Delete</a></td>
                        <?php if($user['admin']): ?>
                            <td><a href="?demote=<?= $user['userID'] ?>">Make Regular User</a></td>
                        <?php else: ?>
                            <td><a href="?promote=<?= $user['userID'] ?>">Make Admin</a></td>
                        <?php endif ?>
                    <?php else: ?>
                        <td></td>
                        <td></td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>

    </div>
        
    <?php include('footer.php'); ?>
</body>
</html>