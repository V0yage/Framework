<?php
	namespace Controllers;
	
	use Models\Article;
	use Views\View;
	
	class MainController {
		private $view;
		private $db;
		
		public function __construct() {
			$this->view = new View(__DIR__ . '/../templates');
		}
		
		public function main() {
			$title = 'Blog';
			$articles = Article::findAll();
			
			$this->view->renderHtml('main/main.php', ['articles' => $articles, 'title' => $title]);
		}
		
		public function sayHello(string $name) {
			$title = 'Greeting';
			$this->view->renderHtml('main/hello.php', ['name' => $name, 'title' => $title]);
		}
	}