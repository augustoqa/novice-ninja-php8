<?php

namespace Ijdb;

use Ninja\DatabaseTable;
use Ijdb\Controllers\Joke as JokeController;
use Ijdb\Controllers\Author as AuthorController;

class JokeWebsite implements \Ninja\Website 
{
	public function getDefaultRoute(): string
	{
		return 'joke/home';
	}

	public function getController(string $controllerName): ?object
	{
		$pdo = new \PDO(
			'mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 
			'ijdbuser', 
			'admin'
		);

		$jokesTable   = new DatabaseTable($pdo, 'joke', 'id');
		$authorsTable = new DatabaseTable($pdo, 'author', 'id');

		if ($controllerName === 'joke') {
			$controller = new JokeController($jokesTable, $authorsTable);
		} else if ($controllerName === 'author') {
			$controller = new AuthorController($authorsTable);
		}

		return $controller ?? null;
	}
}