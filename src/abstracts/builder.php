<?php

	namespace Traineratwot\PDOExtended\abstracts;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Join;
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

		public function where(?callable $callback = NULL)
		: Abstract_Where
		{
			$cls         = $this->driver->tools['Where'];
			$this->where = new $cls($this, $callback);
			return $this->where;
		}

		abstract public function toSql();

		/**
		 * @param string        $table
		 * @param callable|null $callback
		 * @return mixed|Abstract_Join|null
		 */
		public function join(string $table, ?callable $callback = NULL)
		{
			$tbl          = $this->driver->table($table);
			$cls          = $this->driver->tools['Join'];
			$join         = new $cls($this, $tbl, $callback);
			$this->join[] = $join;
			return $join;
		}

		public function joinLeft(string $table, ?string $column = NULL, ?string $leftColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->left($column, $leftColumn);
			$this->join[] = $join;
			return $this;
		}

		public function joinInner(string $table, ?string $column = NULL, ?string $innerColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->inner($column, $innerColumn);
			$this->join[] = $join;
			return $this;
		}

		public function joinRight(string $table, ?string $column = NULL, ?string $rightColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->right($column, $rightColumn);
			$this->join[] = $join;
			return $this;
		}

	}