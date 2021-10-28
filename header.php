<?php
/**************************************************************
 * Name:    Rory Wastle
 * Date:    October 28 2021
 * Purpose: Navigation of a website for a database of albums.
 * ************************************************************/

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <h1 class="navbar-brand">Winnipeg's Classic Albums</h1>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="albums.php">Albums</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="search.php">Search</a>
            </li>
        </ul>
    </div>
    <p class="text-right"><a href="register.php">Login or Register</a></p>
</nav>