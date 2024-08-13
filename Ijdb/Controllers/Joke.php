<?php 

namespace Ijdb\Controllers;

use Ninja\Authentication;
use Ninja\DatabaseTable;

class Joke {

	public function __construct(
		private DatabaseTable $jokesTable, 
		private DatabaseTable $authorsTable,
		private Authentication $authentication){}

	public function home()
	{
		return [
			'template' => 'home.html.php', 
			'title'    => 'Internet Joke Database'
		];
	}

	public function list()
	{	
	    $result = $this->jokesTable->findAll();

	    $jokes = [];
	    foreach ($result as $joke) {
	        $author = $this->authorsTable->find('id', $joke['authorid'])[0];

	        $jokes[] = [
	            'id'       => $joke['id'],
	            'joketext' => $joke['joketext'],
	            'jokedate' => $joke['jokedate'],
	            'authorid' => $joke['authorid'],
	            'name'     => $author['name'],
	            'email'    => $author['email'],
	        ];
	    }

	    $totalJokes =  $this->jokesTable->total();

	    $user = $this->authentication->getUser();

	    return [
			'template'  => 'jokes.html.php', 
			'title'     => 'Joke list',
			'variables' => [
				'totalJokes' => $totalJokes,
				'jokes'      => $jokes,
				'userId'	 => $user['id'] ?? null,
	    	]
	    ];
	}

	public function edit($id = null)
	{
		$author = $this->authentication->getUser();

		if (isset($id)) {
			$joke = $this->jokesTable->find('id', $id)[0] ?? null;
		}

		return [
			'template' => 'editjoke.html.php', 
			'title' => 'Edit joke',
			'variables' => [
				'joke' => $joke ?? null,
				'userId' => $author['id'] ?? null,
			],
		];
	}

	public function editSubmit($id = null)
	{
		$author = $this->authentication->getUser();

		if (isset($id)) {
			$joke = $this->jokesTable->find('id', $id)[0] ?? null;

			if ($joke['authorid'] != $author['id']) {
				return;
			}
		}

		$joke = $_POST['joke'];
		$joke['authorid'] = $author['id'];
		$joke['jokedate'] = new \DateTime();

		$this->jokesTable->save($joke);

		header('location: /joke/list');
	}

	public function deleteSubmit()
	{
		$author = $this->authentication->getUser();

		$joke = $this->jokesTable->find('id', $_POST['id'])[0];

		if ($joke['authorid'] != $author['id']) {
			return;
		}

		$this->jokesTable->delete('id', $_POST['id']);

		header('location: /joke/list');
	}
}