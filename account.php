<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	November 14 2021
 * Purpose: An account page for the current user.
 * ************************************************************/

	session_start();

	//  If no one is logged in, leave this page.
	if(!isset($_SESSION['user'])){
		header("Location: index.php");
		exit();
	}

	require('db_connect.php');

	//  If a post happened and the command was Logout.
    if ($_POST && $_POST['command'] == 'Logout'){
    	//  Remove all session data.
    	$_SESSION = [];

    	//  Return to the home page.
    	header("Location: index.php");
    	exit();
    }
    //  If a post happened and the command was Change Username and the username space is not blank.
    elseif ($_POST && $_POST['command'] == 'Change Username' && $_POST['newname'] != NULL){
    	//  Sanitize the inputed username.
		$username = filter_input(INPUT_POST, 'newname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		//  Select the users.
		$query = "SELECT * FROM users";
	    $statement = $db->prepare($query); // Returns a PDOStatement object.
	    $statement->execute(); // The query is now executed.

	    //  Assume the new name is not already taken.
	    $taken = false;

	    //  While the new name is not taken and there are still users to check.
		while (!$taken && $user = $statement->fetch()) {
			//  If the current name is the same as the new name, it is taken.
			if ($user['name'] == $username) {
				$taken = true;

				$_SESSION['takenUsername'] = true;
			}
		}

		//  If the new name is not taken, update the name.
		if (!$taken) {
    		$query = "UPDATE users SET name = :name WHERE userID = :id";
    		$statement = $db->prepare($query);
    		$statement->bindvalue(":name", $username);
    		$statement->bindvalue(":id", $_SESSION['user']);
    		$statement->execute();

    		if ($statement->execute()) {
    			if (isset($_SESSION['takenUsername'])) {
					unset($_SESSION['takenUsername']);
				}

	            header("Location: account.php");
	            exit;
	        }
		}
    }
    //  If a post happened and the command was to change the password and the password spaces are not blank.
    elseif ($_POST && $_POST['command'] == 'Change Password' && $_POST['newpass1'] != NULL && $_POST['newpass2'] != NULL){
    	//  Sanitize the password inputs.
    	$password1 = filter_input(INPUT_POST, 'newpass1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$password2 = filter_input(INPUT_POST, 'newpass2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    	//  If the password inputs are the same, update the password (hashed).
    	if($password1 == $password2){
    		$query = "UPDATE users SET password = :password WHERE userID = :id";
    		$statement = $db->prepare($query);
    		$statement->bindvalue(":password", password_hash($password1, PASSWORD_DEFAULT));
    		$statement->bindvalue(":id", $_SESSION['user']);

    		if ($statement->execute()) {
	            header("Location: account.php");
	            exit;
	        }
    	}
    }
    //  If there was not a post and the session for the taken username is set, unset it.
    elseif (!$_POST && isset($_SESSION['takenUsername'])) {
    	unset($_SESSION['takenUsername']);
    }

    //  Select information on the current user.
	$query = "SELECT * FROM users WHERE userID = :id";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->bindvalue(":id", $_SESSION['user'], PDO::PARAM_INT);
    $statement->execute(); // The query is now executed.
    $user = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Account Profile</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="newcredentials.js"></script>
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Account Profile: <?= $user['name'] ?></h1>

		<form action="account.php" method="post" class="form-inline">
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Logout" />
            </div>
        </form>

		<h3>Edit Credentials</h3>

		<form action="account.php" method="post" class="form-inline">
            <div class="form-group p-3">
            	<?php if(isset($_SESSION['takenUsername'])): ?>
            		<p class="text-danger">This username is taken.</p>
            	<?php endif ?>
                <label for="newname">New Username</label>
                <input class="form-control" name="newname" id="newname" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Change Username" />
            </div>
        </form>
        <form action="account.php" method="post" class="form-inline">
            <div class="form-group p-3">
                <label for="passlogin">New Password</label>
                <input type="password" class="form-control" name="newpass1" id="newpass1" />
            </div>
            <div class="form-group p-3">
            	<p class="text-danger" id="pass-error">The passwords do not match.</p>
                <label for="passlogin">Confirm New Password</label>
                <input type="password" class="form-control" name="newpass2" id="newpass2" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Change Password" />
            </div>
        </form>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>