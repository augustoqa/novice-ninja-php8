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
	// include __DIR__ . '/../controllers/AuthorController.php';

	$jokesTable   = new DatabaseTable($pdo, 'joke', 'id');
	$authorsTable = new DatabaseTable($pdo, 'author', 'id');

	$action = $_GET['action'] ?? 'home';
	$controllerName = $_GET['controller'] ?? 'joke';

	if ($controllerName === 'joke') {
		$controller = new JokeController($jokesTable, $authorsTable);
	} else if ($controllerName === 'author') {
		$controller = new AuthorController($authorsTable);
	}

	if ($action == strtolower($action) && $controllerName == strtolower($controllerName)) {
		$page = $controller->$action();
	} else {
		http_response_code(301);
		header(sprintf('location: index.php?controller=%s&action=%s',
			strtolower($controllerName),
			strtolower($action)
		));
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