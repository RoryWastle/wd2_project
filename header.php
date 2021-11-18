<?php
/**************************************************************
 * Name:    Rory Wastle
 * Date:    October 28 2021
 * Purpose: Navigation of a website for a database of albums.
 * ************************************************************/

    $currentuser = "";

    if (isset($_SESSION['user'])) {
        $query = "SELECT name FROM users WHERE userID = :id";
        $statement = $db->prepare($query); // Returns a PDOStatement object.
        $statement->bindvalue(":id", $_SESSION['user'], PDO::PARAM_INT);
        $statement->execute(); // The query is now executed.

        $currentuser = $statement->fetch()['name'];
    }

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">

    <h1 class="navbar-brand">Winnipeg's Classic Albums</h1>
    
    <div class="navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="albums.php">Albums</a>
            </li>
            <?php if(isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Add Album</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="genres.php">Genres</a>
                </li>
            <?php endif ?>
        </ul>
    </div>

    <form class="input-group mx-5" action="search.php" method="post">
        <input class="form-control" name="search" id="search" placeholder="Search albums...">
        <input class="btn btn-outline-primary" type="submit" name="command" value="Search">
    </form>

    <?php if(isset($_SESSION['user'])): ?>
        <p class="text-right">Hello, <a href="account.php"><?= $currentuser ?></a>!</p>
    <?php else: ?>
        <p class="text-right"><a href="register.php">Login or Register</a></p>
    <?php endif ?>
</nav>