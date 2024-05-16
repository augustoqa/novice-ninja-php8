<?php

function loadTemplate($templateFileName, $variables)
{
	extract($variables);

	ob_start();

	include __DIR__ . "/../templates/{$templateFileName}";

	return ob_get_clean();
}

try {
	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../classes/DatabaseTable.php';
	include __DIR__ . '/../controllers/JokeController.php';

	$jokesTable   = new DatabaseTable($pdo, 'joke', 'id');
	$authorsTable = new DatabaseTable($pdo, 'author', 'id');

	$jokeController = new JokeController($jokesTable, $authorsTable);

	$action = $_GET['action'] ?? 'home';

	if ($action == strtolower($action)) {
		$page = $jokeController->$action();
	} else {
		http_response_code(301);
		header('location: index.php?action=' . strtolower($action));
		exit;
	}


	$title = $page['title'];

	$variables = $page['variables'] ?? [];
	$output = loadTemplate($page['template'], $variables);
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