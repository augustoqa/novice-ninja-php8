<?php
try {
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';

	if (isset($_POST['joketext'])) {
		updateJoke($pdo, $_POST['jokeid'], $_POST['joketext'], 1);

		header('location: jokes.php');
	} else {
		$joke = getJoke($pdo, $_GET['id']);

		$title = 'Edit joke';

		ob_start();

		include __DIR__ . '/../templates/editjoke.html.php';

		$output = ob_get_clean();
	}
} catch (PDOException $e) {
	$title = 'An error has occurred';

	$output = sprintf(
		'Database error: %s in %s:%s',
		$e->getMessage(),
		$e->getFile(),
		$e->getLine(),
	);
}

include __DIR__ . '/../templates/layout.html.php';
