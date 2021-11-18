<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	October 28 2021
 * Purpose: List of albums for a website for a database of albums.
 * ************************************************************/
    
    session_start();

	require('db_connect.php');

	$query = "SELECT * FROM albums ORDER BY title";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $albums = $statement->fetchall();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Albums</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Albums</h1>

		<form class="input-group" action="albums.php" method="post">
			<label for="genre"></label>
            <input class="form-control" name="genre" id="genre">
            <input class="btn btn-primary" type="submit" name="command" value="Add">
        </form>

		<?php foreach ($albums as $album): ?>
			<div class="card">
				<div class="card-body">
					<?php if($album['coverURL'] != NULL): ?>
						<img src="uploads/thumbnail_<?= $album['coverURL'] ?>" alt="<?= $album['title'] ?> cover." class="thumb" />
					<?php endif ?>
					<h4 class="card-title"><a href="show.php?album=<?= $album['albumID'] ?>"><?= $album['title'] ?></a></h4>
					<h6 class="card-subtitle mb-2 text-muted"><?= $album['artist'] ?> - <?= $album['year'] ?></h6>
					<p><?= $album['likes'] ?> people like this album</p>
				</div>
			</div>
    	<?php endforeach ?>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>