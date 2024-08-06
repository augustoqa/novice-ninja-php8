<?php

namespace Ijdb\Controllers;

class Login 
{
	public function __construct(private \Ninja\Authentication $authentication)
	{
	}

	public function login()
	{
		return [
			'template' => 'loginform.html.php',
			'title' => 'Login in',
		];
	}

	public function loginSubmit()
	{
		$success = $this->authentication->login($_POST['email'], $_POST['password']);

		if ($success) {
			return [
				'template' => 'loginSuccess.html.php',
				'title' => 'Login In Successful',
			];
		} else {
			return [
				'template'  => 'loginform.html.php',
				'title'     => 'Log in',
				'variables' => [
					'errorMessage' => true,
				],
			];
		}
	}

	public function logout()
	{
		$this->authentication->logout();
		header('location: /');
	}
}