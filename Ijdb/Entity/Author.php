<?php

namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Author {
	public int $id;
	public string $name;
	public string $email;
	public string $password;

	public function __construct(private DatabaseTable $jokesTable) {}

	public function getJokes()
	{
		return $this->jokesTable->find('authorid', $this->id);
	}

	public function addJoke(array $joke)
	{
		// set the `authorid` in the new joke to the id stored in this instance
		$joke['authorid'] = $this->id;

		$this->jokesTable->save($joke);
	}
}