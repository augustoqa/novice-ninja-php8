<?php 

namespace Ijdb\Controllers;

use Ninja\DatabaseTable;

class Joke {

	public function __construct(
		private DatabaseTable $jokesTable, 
		private DatabaseTable $authorsTable){}

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
	            'name'     => $author['name'],
	            'email'    => $author['email'],
	        ];
	    }

	    $totalJokes =  $this->jokesTable->total();

	    return [
			'template'  => 'jokes.html.php', 
			'title'     => 'Joke list',
			'variables' => [
				'totalJokes' => $totalJokes,
				'jokes'      => $jokes,
	    	]
	    ];
	}

	public function edit($id = null)
	{
		if (isset($_POST['joke'])) {
			$joke = $_POST['joke'];
			$joke['authorid'] = 1;
			$joke['jokedate'] = new \DateTime();

			$this->jokesTable->save($joke);

			header('location: /joke/list');
		} else {
			$joke = null;

			if (isset($id)) {
				$joke = $this->jokesTable->find('id', $id)[0] ?? null;
			}

			return [
				'template' => 'editjoke.html.php', 
				'title' => 'Edit joke',
				'variables' => ['joke' => $joke]
			];
		}
	}

	public function delete()
	{
		$this->jokesTable->delete('id', $_POST['id']);

		header('location: /joke/list');
	}
}