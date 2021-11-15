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

    session_start();

	//  Select all users.
	$query = "SELECT * FROM users";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.

    //  Assume the inputted information is invalid.
    $valid = false;

    //  Set the id to -1.
    $id = -1;

    //  If a post happened and the command was to login.
    if ($_POST && $_POST['command'] == 'Login'){
    	//  If the username and password spaces were not blank.
    	if ($_POST['userlogin'] != NULL && $_POST['passlogin'] != NULL) {
    		//  Sanitize the inputs.
    		$username = filter_input(INPUT_POST, 'userlogin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password = filter_input(INPUT_POST, 'passlogin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    		//  If the session for no credentials entered was set, unset it.
    		if(isset($_SESSION['noCredentialsLogin'])){
				unset($_SESSION['noCredentialsLogin']);
			}

			//  While the infromation is not valid and there are still users.
    		while (!$valid && $user = $statement->fetch()) {
    			//  If the current user's name is the same as the input.
    		 	if ($user['name'] == $username) {
    		 		//  Verify the inputted password against the one associated with this name.
	    			if (password_verify($password, $user['password'])) {
	    				//  The credentials are valid.
	    				$valid = true;
	    				$id = $user['userID'];

	    				if (isset($_SESSION['invalidCredentialsLogin'])) {
	    					unset($_SESSION['invalidCredentialsLogin']);
	    				}
	    			}
	    		}
    		}

    		//  If the credentials are invalid, create a session variable.
    		if (!$valid) {
    			$_SESSION['invalidCredentialsLogin'] = true;
    		}
    	}
    	//  If the username or password spaces are blank, create a session variable.
    	else {
    		$_SESSION['noCredentialsLogin'] = true;
    	}
    }
    //  If a post happened and the command was Register.
    elseif ($_POST && $_POST['command'] == 'Register') {
    	//  If the username and password fields are not blank.
    	if ($_POST['newname'] != NULL && $_POST['newpass1'] != NULL && $_POST['newpass2'] != NULL) {
    		//  Sanitize the inputs.
    		$username  = filter_input(INPUT_POST, 'newname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password1 = filter_input(INPUT_POST, 'newpass1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    		$password2 = filter_input(INPUT_POST, 'newpass2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    		//  Assume the username has not been taken.
    		$taken = false;

    		//  While the username is not taken and there are still users.
    		while (!$taken && $user = $statement->fetch()) {
    			//  If the current user's name is the same as the input, it is taken.
    			if ($user['name'] == $username) {
    				$taken = true;

    				$_SESSION['takenUsername'] = true;
    			}
    		}

    		//  If the username was not taken.
    		if(!$taken){
    			//  If the passwords are the same.
    			if ($password1 == $password2) {
    				//  This is valid information.
    				$valid = true;

    				//  Add the user to the table.
    				$query = "INSERT INTO users (name, password) VALUES (:name, :password)";
    				$statement = $db->prepare($query);
    				$statement->bindvalue(":name", $username);
    				$statement->bindvalue(":password", password_hash($password1, PASSWORD_DEFAULT));
    				$statement->execute();

    				$id = getMostRecent($db);
    			}

    			//  If the session for a taken username was set, unset it.
    			if (isset($_SESSION['takenUsername'])) {
    				unset($_SESSION['takenUsername']);
    			}
    		}
    	}
    }
    //  If there was not post, unset any session variables to do with logging in or registering an account.
    elseif (!$_POST) {
    	if (isset($_SESSION['takenUsername'])) {
	    	unset($_SESSION['takenUsername']);
		}
		elseif(isset($_SESSION['noCredentialsLogin'])){
			unset($_SESSION['noCredentialsLogin']);
		}
		elseif (isset($_SESSION['invalidCredentialsLogin'])) {
			unset($_SESSION['invalidCredentialsLogin']);
		}
    }

    //  If the information was valid, start a session for the logged in user.
    if($valid){
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
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="newcredentials.js"></script>
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Login</h1>

		<form action="register.php" method="post" class="form-inline">
			<?php if(isset($_SESSION['noCredentialsLogin'])): ?>
				<p class="text-danger">Username and Password are required.</p>
			<?php elseif(isset($_SESSION['invalidCredentialsLogin'])): ?>
				<p class="text-danger">Username or Password is incorrect.</p>
			<?php endif ?>
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
            	<?php if(isset($_SESSION['takenUsername'])): ?>
            		<p class="text-danger">This username is taken.</p>
            	<?php endif ?>
                <label for="newname">Username</label>
                <input class="form-control" name="newname" id="newname" />
            </div>
            <div class="form-group p-3">
                <label for="newpass1">Password</label>
                <input type="password" class="form-control" name="newpass1" id="newpass1" />
            </div>
            <div class="form-group p-3">
            	<p class="text-danger" id="pass-error">The passwords to not match.</p>
                <label for="newpass2">Confirm Password</label>
                <input type="password" class="form-control" name="newpass2" id="newpass2" />
            </div>
            <div class="form-group p-3">
                <input class="btn btn-primary" type="submit" name="command" value="Register" />
            </div>
        </form>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>