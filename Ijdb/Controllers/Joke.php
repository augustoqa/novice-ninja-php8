<?php

namespace Ijdb\Controllers;

use Ijdb\Entity\Author;
use Ninja\Authentication;
use Ninja\DatabaseTable;

class Joke {

	public function __construct(
		private DatabaseTable $jokesTable, 
		private DatabaseTable $authorsTable,
		private DatabaseTable $categoriesTable,
		private Authentication $authentication){}

	public function home()
	{
		return [
			'template' => 'home.html.php', 
			'title'    => 'Internet Joke Database'
		];
	}

	public function list($categoryId = null)
	{	
		if (isset($categoryId)) {
			$category = $this->categoriesTable->find('id', $categoryId)[0];
			$jokes = $category->getJokes();
		} else {
	    	$jokes = $this->jokesTable->findAll();
		}
		
	    $totalJokes =  $this->jokesTable->total();
	    $user = $this->authentication->getUser();

	    return [
			'template'  => 'jokes.html.php', 
			'title'     => 'Joke list',
			'variables' => [
				'totalJokes' => $totalJokes,
				'jokes'      => $jokes,
				'user'	 => $user,
				'categories' => $this->categoriesTable->findAll(),
	    	]
	    ];
	}

	public function edit($id = null)
	{
		$author = $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();

		if (isset($id)) {
			$joke = $this->jokesTable->find('id', $id)[0] ?? null;
		}

		return [
			'template' => 'editjoke.html.php', 
			'title' => 'Edit joke',
			'variables' => [
				'joke' => $joke ?? null,
				'user' => $author,
				'categories' => $categories,
			],
		];
	}

	public function editSubmit($id = null)
	{
		$author = $this->authentication->getUser();

		if (isset($id)) {
			$joke = $this->jokesTable->find('id', $id)[0] ?? null;

			if ($joke->authorid != $author->id 
				&& !$author->hasPermission(\Ijdb\Entity\Author::EDIT_JOKE)) {
				return;
			}
		}

		$joke = $_POST['joke'];
		$joke['jokedate'] = new \DateTime();

		$jokeEntity = $author->addJoke($joke);

		$jokeEntity->clearCategories();

		foreach ($_POST['category'] as $categoryId) {
			$jokeEntity->addCategory($categoryId);
		}

		header('location: /joke/list');
	}

	public function deleteSubmit()
	{
		$author = $this->authentication->getUser();

		$joke = $this->jokesTable->find('id', $_POST['id'])[0];

		if ($joke->authorid != $author->id 
			&& !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKE)) {
			return;
		}

		$this->jokesTable->delete('id', $_POST['id']);

		header('location: /joke/list');
	}
}