<?php
	namespace Models;
	
	class User extends Model {
		protected $nickname;
		protected $email;
		protected $isConfirmed;
		protected $role;
		protected $passwordHash;
		protected $authToken;
		protected $createdAt;
		
		public function getEmail(): string {
			return $this->email;
		}
		
		public function getNickname(): string {
			return $this->nickname;
		}
		
		public static function getTableName(): string {
			return 'users';
		}
	}