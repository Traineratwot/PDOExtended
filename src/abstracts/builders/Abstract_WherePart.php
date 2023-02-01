<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;


	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\abstracts\Driver;

	abstract class Abstract_WherePart
	{
		public string         $where = '';
		public Driver         $driver;
		public Abstract_Where $scope;
		public builder        $DoubleScope;

		public function __construct(Driver $driver, Abstract_Where $scope)
		{
			$this->driver      = $driver;
			$this->scope       = $scope;
			$this->DoubleScope = $scope->scope;
		}

		public function eq(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->eq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function notEq(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->notEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function greater(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->greater;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function greaterEq(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->greaterEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function less(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->greater;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function lessEq(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->greaterEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function in(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->in;
			$this->where = "$column $sign ($key)";
			return $this;
		}

		public function like(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = "LIKE";
			$this->where = "$column $sign $key";
			return $this;
		}

		public function notIn(string $column, $key)
		: Abstract_WherePart
		{
			$column      = $this->driver->escapeColumn($column, $this->DoubleScope->table);
			$sign        = $this->driver->notIn;
			$this->where = "$column $sign ($key)";
			return $this;
		}

		/**
		 * @return string
		 */
		public function get()
		: string
		{
			return $this->where;
		}

	}