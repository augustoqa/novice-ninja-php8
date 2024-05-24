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

	$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

	if ($uri == '') {
		$uri = 'joke/home';
	}

	$route = explode('/', $uri);

	$controllerName = array_shift($route);
	$action = array_shift($route);

	if ($controllerName === 'joke') {
		$controller = new JokeController($jokesTable, $authorsTable);
	} else if ($controllerName === 'author') {
		$controller = new AuthorController($authorsTable);
	}

	if ($uri == strtolower($uri)) {
		$page = $controller->$action(...$route);
	} else {
		http_response_code(301);
		header(header("location: /{strtolower($uri)}"));
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