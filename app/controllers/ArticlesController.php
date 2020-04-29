<?php
	namespace Controllers;
	
	use Views\View;
	use Models\Article;
	use Models\User;
	use Exceptions\NotFoundException;
	
	class ArticlesController {
		private $view;
		private $db;
		
		public function __construct() {
			$this->view = new View(__DIR__ . '/../templates');
		}
		
		public function view(int $articleId) {
			$article = Article::getById($articleId);
			
			if (!isset($article)) {
				throw new NotFoundException();
			}
			
			$this->view->renderHtml('articles/view.php', ['article' => $article]);
		}
		
		public function edit($articleId): void {
			$article = Article::getById($articleId);
			
			if ($article === null) {
				throw new NotFoundException();
			}
			
			$article->setName('New articles name');
			$article->setText('New articles text');
			
			$article->save();
		}
		
		public function add(): void {
			$author = User::getById(1);
			
			$article = new Article();
			$article->setName('Article name');
			$article->setText('Article text');
			$article->setAuthor($author);

			$article->save();
			
			var_dump($article);
		}
		
		public function delete(int $articleId): void {
			$article = Article::getById($articleId);
			
			if (!isset($article)) {
				$this->view->renderHtml('errors/404.php', [], 404);
				return;
			}
			
			$article->delete();
			
			var_dump('Articles delete success');
		}
	}