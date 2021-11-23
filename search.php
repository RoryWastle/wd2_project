<?php
/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	October 28 2021
 * Purpose: Search albums of a website for a database of albums.
 * ************************************************************/
    
    session_start();

	require('db_connect.php');

	$query = "SELECT * FROM genres ORDER BY genre";
    $statement = $db->prepare($query);
    $statement->execute();
    $genres = $statement->fetchAll();

    $tosearch = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $keyword  = strtolower($tosearch);

    $selectedGenre = "";

    //  If a genre was specified.
    if (isset($_GET['genre']) && $_GET['genre'] > 0) {
    	//  Sanitize the genre parameter.
    	$selectedGenre = filter_input(INPUT_GET, 'genre', FILTER_SANITIZE_NUMBER_INT);

    	$query = "SELECT * FROM albums a JOIN albumgenre g ON a.albumID = g.albumID" 
    			."WHERE g.genreID = :genreID AND LOWER(a.title) LIKE '%{$keyword}%' OR LOWER(a.artist) LIKE '%{$keyword}%' ORDER BY title";
	    $statement = $db->prepare($query); // Returns a PDOStatement object.
	    $statement->bindvalue(":genreID", $selectedGenre);
	    $statement->execute(); // The query is now executed.
    }
    else {
    	$query = "SELECT * FROM albums WHERE LOWER(title) LIKE '%{$keyword}%' OR LOWER(artist) LIKE '%{$keyword}%' ORDER BY title";
    	$statement = $db->prepare($query); // Returns a PDOStatement object.
    	$statement->execute(); // The query is now executed.
    }

    $albums = $statement->fetchall();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Winnipeg's Classic Albums | Search</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include('header.php'); ?>

	<div class="container">
		<h1 class="pt-3">Search</h1>
		<h4 class="pt-3"><?= count($albums) ?> results for "<?= $tosearch ?>"</h4>

		<form class="input-group" action="search.php">
			<input type="hidden" name="search" value="<?= $tosearch ?>">
			<select class="form-control" id="genre" name="genre">
                <option value="0">No filter</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="0">All genres</option>
	                <?php foreach ($genres as $genre): ?>
	                	<option 
	                        value=<?= $genre['genreID'] ?> 
	                        <?php if(isset($_GET['genre']) && $genre['genreID'] == $selectedGenre): ?> selected <?php endif ?> >
	                        <?= $genre['genre'] ?>
	                            </option>
	                <?php endforeach ?>
                <?php endforeach ?>
            </select>
            <input class="btn btn-outline-primary" type="submit" value="Filter by Genre">
        </form>

        <br />

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