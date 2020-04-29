<?php
	return [
		'!^articles/(\d+)$!' => [Controllers\ArticlesController::class, 'view'],
		'!^articles/(\d+)/edit$!' => [Controllers\ArticlesController::class, 'edit'],
		'!^articles/(\d+)/delete$!' => [Controllers\ArticlesController::class, 'delete'],
		'!^articles/add$!' => [Controllers\ArticlesController::class, 'add'],
		'!^$!' => [Controllers\MainController::class, 'main']
	];