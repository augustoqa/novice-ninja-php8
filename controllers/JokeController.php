<?php 

class JokeController {

	public function __construct(
		private DatabaseTable $jokesTable, 
		private DatabaseTable $authorsTable){}

	public function home()
	{
		ob_start();

		include __DIR__ . '/../templates/home.html.php';

		$output = ob_get_clean();

		return [
			'output' => $output, 
			'title'  => 'Internet Joke Database',
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

	    ob_start();

	    include __DIR__ . '/../templates/jokes.html.php';

	    $output = ob_get_clean();

	    return ['output' => $output, 'title' => 'Joke list'];
	}

	public function edit()
	{
		if (isset($_POST['joke'])) {
			$joke = $_POST['joke'];
			$joke['authorid'] = 1;
			$joke['jokedate'] = new DateTime();

			$this->jokesTable->save($joke);

			header('location: index.php?action=list');
		} else {
			$joke = null;

			if (isset($_GET['id'])) {
				$joke = $this->jokesTable->find('id', $_GET['id'])[0] ?? null;
			}

			ob_start();

			include __DIR__ . '/../templates/editjoke.html.php';

			$output = ob_get_clean();

			return ['output' => $output, 'title' => 'Edit joke'];
		}
	}

	public function delete()
	{
		$this->jokesTable->delete('id', $_POST['id']);

		header('location: index.php?action=list');
	}
}