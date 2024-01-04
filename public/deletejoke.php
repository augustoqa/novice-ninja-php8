<?php

try {
	include __DIR__ . '/../includes/DatabaseConnection.php';

	$sql = 'DELETE FROM `joke` WHERE `id` = :id';

	$stmt = $pdo->prepare($sql);

	$stmt->bindValue(':id', $_POST['id']);
	$stmt->execute();

	header('location: jokes.php');
} catch (PDOException $e) {
	$title = 'An error has occurred';

	$output = sprintf(
		'Unable to connect to the database server: %s in %s:%s',
		$e->getMessage(),
		$e->getFile(),
		$e->getLine(),
	);
}

include __DIR__ . '/../templates/layout.html.php';