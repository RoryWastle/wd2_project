<?php
/**********************************************************************
 * Author:  Rory Wastle
 * Date:    November 18 2021
 * Purpose: Edits or deletes genre from the genres table.
 **********************************************************************/
	
    session_start();

    require('db_connect.php');
	
	//  Assume the info is valid.
	$valid = true;

    //  If the command was to edit.
    if ($_POST['command'] == 'Edit' && $_POST['editgenre'] != NULL) {
        //  Sanitize the edited post.
        $updated = filter_input(INPUT_POST, 'editgenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //  Update the requested genre in the table.
        $query = "UPDATE genres SET genre = :genre WHERE genreID = :genreID";
        $statement = $db->prepare($query);
        $statement->bindValue(":genre", $updated);
    }
    //  If the command was to delete
    elseif ($_POST['command'] == 'Delete') {
        //  Delete the requested genre from the table.
        $query = "DELETE FROM genres WHERE genreID = :genreID";
        $statement = $db->prepare($query);
    }
    else {
    	//  The info is not valid.
    	$valid = false;
    }

    //  If the info was valid
    if ($valid) {
    	$statement->bindValue(":genreID", $_POST['genreID'], PDO::PARAM_INT);

	    if ($statement->execute()) {
	        header("Location: genres.php");
	        exit;
	    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Winnipeg's Classic Albums | Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <?php if(!$valid): ?>
        <div id="wrapper">
            <div class="p-3">
                <h2>An error occurred when editing the genre.</h2>
                <p>The genre must be at least one character.</p>
                <p><a href="genres.php">Return to Genres</a></p>
            </div>
        </div> 
    <?php endif ?>
</body>
</html>