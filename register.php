<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	November 14 2021
 * Purpose: Login or Register for a website for a database of albums.
 * ************************************************************/

	//  Gets the most recent user added.
    function getMostRecent($db){
        $query = "SELECT MAX(userID) AS latest FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        $row = $statement->fetch();
        return $row['latest'];
    }

	require('db_connect.php');

	$query = "SELECT * FROM users";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.

    $users = $statement->fetchall();

    $valid = false;

    $id = -1;

    if ($_POST && $_POST['command'] == 'Login'){
    	if ($_POST['userlogin'] != NULL && $_POST['passlogin'] != NULL) {
    		$username = filter_input(INPUT_POST, 'userlogin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password = filter_input(INPUT_POST, 'passlogin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    		foreach ($users as $user) {
	    		if ($user['name'] == $username) {
	    			if ($user['password'] == $password) {
	    				$valid = true;
	    				$id = $user['userID'];
	    			}
	    		}
    		}
    	}
    }
    elseif ($_POST && $_POST['command'] == 'Register') {
    	if ($_POST['userregister'] != NULL && $_POST['pass1register'] != NULL && $_POST['pass2register'] != NULL) {
    		$username  = filter_input(INPUT_POST, 'userregister', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password1 = filter_input(INPUT_POST, 'pass1register', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password2 = filter_input(INPUT_POST, 'pass2register', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    		$taken = false;

    		foreach ($users as $user){
    			if ($user['name'] == $username) {
    				$taken = true;
    			}
    		}

    		if(!$taken){
    			if ($password1 == $password2) {
    				$valid = true;

    				$query = "INSERT INTO users (name, password) VALUES (:name, :password)";
    				$statement = $db->prepare($query);
    				$statement->bindvalue(":name", $username);
    				$statement->bindvalue(":password", $password1);
    				$statement->execute();

    				$id = getMostRecent($db);
    			}
    		}
    	}
    }

    if($valid){
    	session_start();
    	$_SESSION['user'] = $id;

    	header("Location: index.php");
    	exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Login or Register</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!--script src="register.js"></script-->
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Login</h1>

		<form action="register.php" method="post" class="form-inline">
            <div class="form-group p-3">
                <label for="userlogin">Username</label>
                <input class="form-control" name="userlogin" id="userlogin" />
            </div>
            <div class="form-group p-3">
                <label for="passlogin">Password</label>
                <input type="password" class="form-control" name="passlogin" id="passlogin" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Login" />
            </div>
        </form>

        <h1 class="pt-3">Register</h1>

		<form action="register.php" method="post" class="form-inline">
            <div class="form-group p-3">
                <label for="userregister">Username</label>
                <input class="form-control" name="userregister" id="userregister" />
            </div>
            <div class="form-group p-3">
                <label for="pass1register">Password</label>
                <input type="password" class="form-control" name="pass1register" id="pass1register" />
            </div>
            <div class="form-group p-3">
                <label for="pass2register">Confirm Password</label>
                <input type="password" class="form-control" name="pass2register" id="pass2register" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Register" />
            </div>
        </form>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>