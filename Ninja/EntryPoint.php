<?php

namespace Ninja;

class EntryPoint {

	public function __construct(private Website $website){}

	private function loadTemplate($templateFileName, $variables = [])
	{
		extract($variables);

		ob_start();

		include __DIR__ . "/../templates/{$templateFileName}";

		return ob_get_clean();
	}

	private function checkURI($uri)
	{
		if ($uri != strtolower($uri)) {
			http_response_code(301);
			header("location: {strtolower($uri)}");
		}
	}

	public function run(string $uri, string $method)
	{
		try {
			$this->checkURI($uri);

			if ($uri == '') {
				$uri = $this->website->getDefaultRoute();
			}

			$route = explode('/', $uri);

			$controllerName = array_shift($route);
			$action = array_shift($route);

			if ($method === 'POST') {
				$action .= 'Submit';
			}

			$controller = $this->website->getController($controllerName);

			$page = $controller->$action(...$route);

			$title = $page['title'];

			$variables = $page['variables'] ?? [];
			$output = $this->loadTemplate($page['template'], $variables);
		} catch (PDOException $e) {
			$title = 'An error has occurred';

			$output = sprintf(
				'Unable to connect to the database server: %s in %s:%s',
				$e->getMessage(),
				$e->getFile(),
				$e->getLine(),
			);
		}

		include __DIR__ . '/../templates/layout.html.php';
	}

}