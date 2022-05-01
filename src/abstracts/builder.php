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

		public function where($callback = NULL)
		: Abstract_Where
		{
			$cls         = $this->driver->tools['Where'];
			$this->where = new $cls($this, $this->driver, $callback);
			return $this->where;
		}

		abstract public function toSql();
	}