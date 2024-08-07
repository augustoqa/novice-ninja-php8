<?php

namespace Ijdb;

use Ijdb\Controllers\Author as AuthorController;
use Ijdb\Controllers\Joke as JokeController;
use Ijdb\Controllers\Login as LoginController;
use Ninja\Authentication;
use Ninja\DatabaseTable;

class JokeWebsite implements \Ninja\Website 
{
	private DatabaseTable $jokesTable;
	private DatabaseTable $authorsTable;
	private Authentication $authentication;

	public function __construct()
	{
		$pdo = new \PDO(
			'mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 
			'ijdbuser', 
			'admin'
		);

		$this->jokesTable   = new DatabaseTable($pdo, 'joke', 'id');
		$this->authorsTable = new DatabaseTable($pdo, 'author', 'id');

		$this->authentication = new Authentication($this->authorsTable, 'email', 'password');
	}

	public function getLayoutVariables(): array
	{
		return ['loggedIn' => $this->authentication->isLoggedIn()];
	}

	public function getDefaultRoute(): string
	{
		return 'joke/home';
	}

	public function getController(string $controllerName): ?object
	{
		if ($controllerName === 'joke') {
			$controller = new JokeController(
				$this->jokesTable, 
				$this->authorsTable, 
				$this->authentication
			);
		} else if ($controllerName === 'author') {
			$controller = new AuthorController($this->authorsTable);
		} else if ($controllerName === 'login') {
			$controller = new LoginController($this->authentication);
		}

		return $controller ?? null;
	}

	public function checkLogin(string $uri): ?string
	{
		$restrictedPages = ['joke/edit', 'joke/delete'];

		if (in_array($uri, $restrictedPages) && !$this->authentication->isLoggedIn()) {
			header('location: /login/login');
			exit();
		}

		return $uri;
	}
}