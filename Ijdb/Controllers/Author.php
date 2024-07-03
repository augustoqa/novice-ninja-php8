<?php

namespace Ijdb\Controllers;

use Ninja\DatabaseTable;

class Author 
{
	public function __construct(private DatabaseTable $authorsTable) {}

	public function registrationForm()
	{
		return [
			'template' => 'register.html.php', 
			'title' => 'Register an account',
		];
	}

	public function success()
	{
		return [
			'template' => 'registersuccess.html.php',
			'title' => 'Registration Successful',
		];
	}

	public function registrationFormSubmit()
	{
		$author = $_POST['author'];

		$errors = [];

		if (empty($author['name'])) {
			$errors[] = 'Name cannot be blank';
		}

		if (empty($author['email'])) {
			$errors[] = 'Email cannot be blank';
		} elseif (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'Invalid email address';
		} else { // check if the email already exists
			$author['email'] = strtolower($author['email']);

			if (count($this->authorsTable->find('email', $author['email'])) > 0) {
				$errors[] = 'That email address is already registered';
			}
		}

		if (empty($author['password'])) {
			$errors[] = 'Password cannot be blank';
		}

		if (empty($errors)) {
			$author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

			$this->authorsTable->save($author);

			header('Location: /author/success');
		} else {
			return [
				'template'  => 'register.html.php',
				'title'     => 'Register an account',
				'variables' => [
					'errors' => $errors,
					'author' => $author,
				],
			];
		}
	}
}