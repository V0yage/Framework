<?php
	spl_autoload_register(function (string $className) {
		$classFilePath = __DIR__ . '/app/' . convertNamespaceToPath($className) . '.php';
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
		echo 'Page not founded!';
		return;
	}
	
	$controllerName = $controllerAndAction[0];
	$actionName = $controllerAndAction[1];
	
	unset($matches[0]);
	
	$controller = new $controllerName();
	$controller->$actionName(...$matches);
	

	function convertNamespaceToPath($namespace) {
		return str_replace('\\', '/', $namespace);
	}