<?php

namespace Ijdb\Entity;

class Joke {
	public int $id;
	public int $authorid;
	public string $jokedate;
	public string $joketext;
	private ?object $author;

	public function __construct(private \Ninja\DatabaseTable $authorsTable)
	{
	}

	public function getAuthor()
	{
		if (empty($this->author)) {
			$this->author = $this->authorsTable->find('id', $this->authorid)[0];
		}

		return $this->author;
	}
}