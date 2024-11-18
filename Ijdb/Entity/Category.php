<?php

namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Category {
	public $id;
	public $name;

	public function __construct(
		private DatabaseTable $jokesTable, 
		private ?DatabaseTable $jokeCategoriesTable)
	{
	}

	public function getJokes()
	{
		$jokeCategories = $this->jokeCategoriesTable->find('categoryId', $this->id);

		$jokes = [];

		foreach ($jokeCategories as $jokeCategory) {
			$joke = $this->jokesTable->find('id', $jokeCategory->jokeId)[0] ?? null;

			if ($joke) {
				$jokes[] = $joke;
			}
		}

		return $jokes;
	}
}