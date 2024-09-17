<?php

namespace Ijdb\Entity;

class Joke {
	public int $id;
	public int $authorid;
	public string $jokedate;
	public string $joketext;

	public function __construct(private \Ninja\DatabaseTable $authorsTable)
	{
	}

	public function getAuthor()
	{
		return $this->authorsTable->find('id', $this->authorid)[0];
	}
}