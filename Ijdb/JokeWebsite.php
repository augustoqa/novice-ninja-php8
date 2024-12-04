<?php

namespace Ijdb;

use Ijdb\Controllers\Author as AuthorController;
use Ijdb\Controllers\Category as CategoryController;
use Ijdb\Controllers\Joke as JokeController;
use Ijdb\Controllers\Login as LoginController;
use Ijdb\Entity\{Joke, Author, Category};
use Ninja\Authentication;
use Ninja\DatabaseTable;

class JokeWebsite implements \Ninja\Website 
{
	private ?DatabaseTable $jokesTable;
	private ?DatabaseTable $authorsTable;
    private ?DatabaseTable $categoriesTable;
    private ?DatabaseTable $jokeCategoriesTable;
	private Authentication $authentication;

	public function __construct()
	{
		$pdo = new \PDO(
			'mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 
			'ijdbuser', 
			'admin'
		);

		$this->jokesTable   = new DatabaseTable(
			$pdo, 'joke', 'id', Joke::class, [&$this->authorsTable, &$this->jokeCategoriesTable]
		);
		$this->authorsTable = new DatabaseTable(
			$pdo, 'author', 'id', Author::class, [&$this->jokesTable]
		);
        $this->categoriesTable = new DatabaseTable(
        	$pdo, 'category', 'id', Category::class, [&$this->jokesTable, &$this->jokeCategoriesTable]
        );

		$this->authentication = new Authentication($this->authorsTable, 'email', 'password');
		$this->jokeCategoriesTable = new DatabaseTable($pdo, 'joke_category', 'categoryId');
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
        $controllers = [
            'joke' => new JokeController(
            	$this->jokesTable, 
            	$this->authorsTable, 
            	$this->categoriesTable,
            	$this->authentication
            ),
			'author' => new AuthorController($this->authorsTable),
			'login' => new LoginController($this->authentication),
            'category' => new CategoryController($this->categoriesTable),
        ];

		return $controllers[$controllerName] ?? null;
	}

	public function checkLogin(string $uri): ?string
	{
		$restrictedPages = [
			'category/list' => Author::LIST_CATEGORIES
		];

		if (isset($restrictedPages[$uri])) {
			if (!$this->authentication->isLoggedIn() ||
				!$this->authentication->getUser()->hasPermission($restrictedPages[$uri])) {
				header('location: /login/login');
				exit();
			}
		}

		return $uri;
	}
}