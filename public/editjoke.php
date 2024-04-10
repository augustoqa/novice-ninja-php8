<?php
try {
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../includes/DatabaseFunctions.php';

	if (isset($_POST['joke'])) {
		$joke = $_POST['joke'];
		$joke['authorid'] = 1;
		$joke['jokedate'] = new DateTime();

		save($pdo, 'joke', 'id', $joke);

		header('location: jokes.php');
	} else {
		$joke = null;

		if (isset($_GET['id'])) {
			$joke = find($pdo, 'joke', 'id', $_GET['id'])[0] ?? null;
		}

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
