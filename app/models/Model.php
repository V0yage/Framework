<?php
	namespace Models;
	
	use Services\Db;
	
	abstract class Model {
		protected $id;
		
		public function getId(): int {
			return $this->id;
		}		
		
		public function __set($name, $value) {
			$camelCaseName = $this->underscoreToCamelCase($name);
			$this->$camelCaseName = $value;
		}
		
		private function underscoreToCamelCase(string $source): string {
			return lcfirst(str_replace('_', '', ucwords($source, '_')));
		}
		
		private function camelcaseToUnderscore(string $source): string {
			return strtolower(preg_replace('![A-Z]!', '_$0', $source));
		}
		
		private function mapPropertiesToDbFormat(): array {
			$reflector = new \ReflectionObject($this);
			$properties = $reflector->getProperties();
			
			$mappedProperties = [];
			foreach ($properties as $property) {
				$propertyName = $property->getName();
				$underscorePropertyName = $this->camelcaseToUnderscore($propertyName);
				$mappedProperties[$underscorePropertyName] = $this->$propertyName;
			}
			
			return $mappedProperties;
		}
		
		public function save(): void {
			$mappedProperties = $this->mapPropertiesToDbFormat();
			
			if ($this->id !== null) {
				$this->update($mappedProperties);
			} else {
				$this->insert($mappedProperties);
			}
		}
		
		public function insert(array $mappedProperties): void {
			$filteredProps = array_filter($mappedProperties);
		
			$queryFieldsList = array_keys($filteredProps);
			$queryValuesList = [];
			$paramsSubstitutions = [];
			
			foreach ($filteredProps as $prop => $value) {
				$paramLabel = ':' . $prop;
				$queryValuesList[] = $paramLabel;
				$paramsSubstitutions[$paramLabel] = $value;
			}
			
			$query = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $queryFieldsList) .') ' .
					 'VALUES (' . implode(', ', $queryValuesList) . ')';
			$db = Db::getInstance();
			$db->query($query, $paramsSubstitutions, static::class);
			$this->id = $db->getLastInsertId();
			$this->refresh();
		}
		
		public function update(array $mappedProperties): void {
			$queryParamsList = [];
			$paramsSubstitutions = [];
			$idx = 1;
			
			foreach ($mappedProperties as $prop => $value) {
				$paramLabel = ':param' . $idx++;
				$queryParamsList[$prop] =  $prop . ' = ' . $paramLabel;
				$paramsSubstitutions[$paramLabel] = $value;
			}
			
			$query = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $queryParamsList) . ' WHERE id = ' . $this->id;
			$db = Db::getInstance();
			$db->query($query, $paramsSubstitutions, static::class);
		}
		
		public function refresh(): void {
			$dbObject = static::getById($this->id);
			
			$properties = get_object_vars($dbObject);
			foreach ($properties as $name => $value) {
				$this->$name = $value;
			}
		}
		
		public function delete(): void {
			$db = Db::getInstance();
			$db->query(
				'DELETE FROM ' . static::getTableName() . ' WHERE id = :id',
				[':id' => $this->id]
			);
			$this->id = null;
		}
		
		public static function getById(int $id): ?self {
			$db = Db::getInstance();
			$entities = $db->query(
				'SELECT * FROM `' . static::getTableName() . '` WHERE id = :id',
				[':id' => $id],
				static::class
			);
			return $entities ? $entities[0] : null;
		}
		
		public static function findAll(): array {
			$db = Db::getInstance();
			return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
		}
		
		abstract protected static function getTableName(): string;
	}