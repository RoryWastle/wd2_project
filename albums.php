<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	October 28 2021
 * Purpose: List of albums for a website for a database of albums.
 * ************************************************************/
    
    session_start();

	require('db_connect.php');

	$query = "SELECT * FROM albums";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Albums</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Albums</h1>

		<?php while ($row = $statement->fetch()): ?>
			<div class="card">
				<div class="card-body">
					<?php if($row['coverURL'] != NULL): ?>
						<img src="uploads/thumbnail_<?= $row['coverURL'] ?>" alt="<?= $row['title'] ?> cover." class="thumb" />
					<?php endif ?>
					<h4 class="card-title"><a href="show.php?album=<?= $row['albumID'] ?>"><?= $row['title'] ?></a></h4>
					<h6 class="card-subtitle mb-2 text-muted"><?= $row['artist'] ?> - <?= $row['year'] ?></h6>
					<p><?= $row['likes'] ?> people like this album</p>
				</div>
			</div>
    	<?php endwhile ?>
	</div>

	<?php include('footer.php'); ?>
</body>
</html>