<?php

namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Joke {
	public int $id;
	public int $authorid;
	public string $jokedate;
	public string $joketext;
	private ?object $author;

	public function __construct(
		private DatabaseTable $authorsTable,
		private DatabaseTable $jokeCategoriesTable)
	{
	}

	public function getAuthor()
	{
		if (empty($this->author)) {
			$this->author = $this->authorsTable->find('id', $this->authorid)[0];
		}

		return $this->author;
	}

	public function addCategory($categoryId)
	{
		$jokeCat = ['jokeId' => $this->id, 'categoryId' => $categoryId];

		$this->jokeCategoriesTable->save($jokeCat);
	}
}