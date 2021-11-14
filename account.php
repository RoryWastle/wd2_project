<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	November 14 2021
 * Purpose: An account page for the current user.
 * ************************************************************/

	session_start();

	if(!isset($_SESSION['user'])){
		header("Location: index.php");
		exit();
	}

	require('db_connect.php');

	$query = "SELECT * FROM users WHERE userID = :id";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->bindvalue(":id", $_SESSION['user'], PDO::PARAM_INT);
    $statement->execute(); // The query is now executed.
    $user = $statement->fetch();

    if ($_POST && $_POST['command'] == 'Logout'){
    	$_SESSION = [];

    	header("Location: index.php");
    	exit();
    }
    elseif ($_POST && $_POST['command'] == 'Change Username' && $_POST['newname'] != NULL){
		$username = filter_input(INPUT_POST, 'newname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM users";
	    $statement = $db->prepare($query); // Returns a PDOStatement object.
	    $statement->execute(); // The query is now executed.
	    $users = $statement->fetchall();

	    $taken = false;

		foreach ($users as $user){
			if ($user['name'] == $username) {
				$taken = true;
			}
		}

		if (!$taken) {
    		$query = "UPDATE users SET name = :name WHERE userID = :id";
    		$statement = $db->prepare($query);
    		$statement->bindvalue(":name", $username);
    		$statement->bindvalue(":id", $_SESSION['user']);
    		$statement->execute();
		}
    }
    elseif ($_POST && $_POST['command'] == 'Change Password' && $_POST['pass1'] != NULL && $_POST['pass2'] != NULL){
    	$password1 = filter_input(INPUT_POST, 'pass1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$password2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    	if($password1 == $password2){
    		$query = "UPDATE users SET password = :password WHERE userID = :id";
    		$statement = $db->prepare($query);
    		$statement->bindvalue(":password", $password1);
    		$statement->bindvalue(":id", $_SESSION['user']);
    		$statement->execute();
    	}
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Account Profile</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
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
                <label for="newname">New Username</label>
                <input class="form-control" name="newname" id="newname" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Change Username" />
            </div>
            <div class="form-group p-3">
                <label for="passlogin">New Password</label>
                <input type="password" class="form-control" name="pass1" id="pass1" />
            </div>
            <div class="form-group p-3">
                <label for="passlogin">Confirm New Password</label>
                <input type="password" class="form-control" name="pass2" id="pass2" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Change Password" />
            </div>
        </form>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>