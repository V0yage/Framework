<?php
	return [
		'!^hello/(.*)$!' => [Controllers\MainController::class, 'sayHello'],
		'!^$!' => [Controllers\MainController::class, 'main']
	];