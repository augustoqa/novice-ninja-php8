<?php

if (isset($_POST['joketext'])) {
	try {
	    include __DIR__ . '/../includes/DatabaseConnection.php';

		$sql = 'INSERT INTO `joke` SET
		    `joketext` = :joketext,
		    `jokedate` = CURDATE()';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':joketext', $_POST['joketext']);

        $stmt->execute();

        header('Location: jokes.php');
	} catch (PDOException $e) {
		$title = 'An error has occurred';
		$output = sprintf(
			'Database error: %s in %s:%s', 
			$e->getMessage(), $e->getFile(), 
			$e->getLine()
		);
	}
} else {
	$title = 'Add a new joke';

	ob_start();

	include __DIR__ . '/../templates/addjoke.html.php';

	$output = ob_get_clean();
}

include __DIR__ . '/../templates/layout.html.php';