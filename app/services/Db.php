<?php
	namespace Services;
	
	use Exceptions\DbException;
	
	class Db {
		private static $instance;
		private $pdo;
		
		public static function getInstance(): self {
			if (!isset(self::$instance)) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		private function __construct() {
			$dbOptions = (require __DIR__ . '/../settings.php')['db'];
			
			try {
				$this->pdo = new \PDO(
					'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
					$dbOptions['user'],
					$dbOptions['password']
				);
				$this->pdo->exec('SET NAMES UTF8');	
			} catch (\PDOException $e) {
				throw new DbException('Database connection error: ' . $e->getMessage());
			}
			
		}
		
		public function query(string $sql, $params = [], string $className = 'stdClass'): ?array {
			$sth = $this->pdo->prepare($sql);
			$result = $sth->execute($params);

			if (false === $result) {
				return null;
			}
			return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
		}
		
		public function getLastInsertId(): int {
			return (int) $this->pdo->lastInsertId();
		}
	}