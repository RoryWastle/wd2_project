<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	October 28 2021
 * Purpose: Homepage of a website for a database of albums.
 * ************************************************************/

	require('db_connect.php');

	$query = "SELECT * FROM albums WHERE likes = (SELECT MAX(likes) FROM albums)";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $album = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Welcome to Winnipeg's Classic Albums!</h1>
		<p>
			This is a website dedicated to the albums that have shaped Winnipeg's classic rock. Albums are added here,
			where they can be commented on and liked by others. This will provide a smooth system to share favourite
			albums and keep classic rock strong in Winnipeg!
		</p>

		<br />
		<hr />
		<br />

		<h3>Winnipeg's most popular classic album</h3>
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
	</div>

	<?php include('footer.php'); ?>
</body>
</html>