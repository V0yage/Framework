<?php
	namespace Models;
	
	use Models\User;
	
	class Article extends Model {
		protected $name;
		protected $text;
		protected $authorId;
		protected $createdAt;
		
		public function setName(string $name) {
			$this->name = $name;
		}
		
		public function setText(string $text) {
			$this->text = $text;
		}
		
		public function setAuthor(User $author) {
			$this->authorId = $author->getId();
		}
		
		public function getName(): string {
			return $this->name;
		}
		
		public function getText(): string {
			return $this->text;
		}
		
		public function getAuthorId(): int {
			return $this->authorId;
		}
		
		public function getAuthor(): User {
			return User::getById($this->authorId);
		}
		
		public static function getTableName(): string {
			return 'articles';
		}
	}