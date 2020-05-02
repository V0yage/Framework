<?php
	use Exceptions\DbException;
	use Exceptions\NotFoundException;
	use Views\View;
	
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	try {
		spl_autoload_register(function (string $className) {
			$classFilePath = __DIR__ . '/app/' . convertNamespaceToPath($className) . '.php';
            $classFilePath = strtolower(pathinfo($classFilePath, PATHINFO_DIRNAME)) . '/' . pathinfo($classFilePath, PATHINFO_BASENAME);
			require_once  $classFilePath;
		});
		
		$route = $_GET['route'] ?? '';
		$routes = require_once __DIR__ . '/app/routes.php';
		
		$isRouteFound = false;
		foreach ($routes as $pattern => $controllerAndAction) {
			preg_match($pattern, $route, $matches);
			if (!empty($matches)) {
				$isRouteFound = true;
				break;
			}
		}

		if (!$isRouteFound) {
			throw new NotFoundException();
		}
		
		$controllerName = $controllerAndAction[0];
		$actionName = $controllerAndAction[1];
		
		unset($matches[0]);
		
		$controller = new $controllerName();
		$controller->$actionName(...$matches);
	} catch (DbException $e) {
		$view = new View(__DIR__ . '/app/templates/errors');
		$view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
	} catch (NotFoundException $e) {
		$view = new View(__DIR__ . '/app/templates/errors');
		$view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
	}

	function convertNamespaceToPath($namespace) {
		return str_replace('\\', '/', $namespace);
	}