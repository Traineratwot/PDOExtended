<?php

	namespace Traineratwot\PDOExtended\abstracts;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Where;
	use Traineratwot\PDOExtended\tableInfo\PDOEBdObject;
	use Traineratwot\PDOExtended\tableInfo\Scheme;

	abstract class builder
	{
		public string          $table = '';
		public Driver          $driver;
		public ?Abstract_Where $where = NULL;
		public PDOEBdObject    $scope;
		public Scheme          $scheme;
		/**
		 * @var mixed
		 */
		public array $join = [];

		public function __construct(PDOEBdObject $scope)
		{
			$this->scope  = $scope;
			$this->driver = $scope->driver;
			$this->scheme = $scope->scheme;
		}

		/**
		 * @param string $table
		 * @return $this
		 */
		public function setTable(string $table)
		: builder
		{
			$this->table = $this->driver->escapeTable($table);
			return $this;
		}

		/**
		 * @param callable|null|mixed $keyOrCallback callback or primary key value
		 * @return Abstract_Where
		 */
		public function where($keyOrCallback = NULL)
		: Abstract_Where
		{
			$cls         = $this->driver->tools['Where'];
			$this->where = new $cls($this, $keyOrCallback);
			return $this->where;
		}

		public function run(string &$sql = NULL)
		{
			$sql = $this->toSql();
			return $this->scope->driver->connection->query($sql);
		}

		abstract public function toSql();
	}