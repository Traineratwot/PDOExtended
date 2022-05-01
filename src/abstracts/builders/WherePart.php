<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;


	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\abstracts\Driver;

	abstract class WherePart
	{
		private string  $where = '';
		private Driver  $driver;
		private builder $scope;

		public function __construct(Driver $driver,Builder $scope)
		{
			$this->driver = $driver;
			$this->scope = $scope;
		}

		public function eq(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->eq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function notEq(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->notEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function greater(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->greater;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function greaterEq(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->greaterEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function less(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->greater;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function lessEq(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->greaterEq;
			$this->where = "$column $sign $key";
			return $this;
		}

		public function in(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
			$sign        = $this->driver->in;
			$this->where = "$column $sign ($key)";
			return $this;
		}

		public function notIn(string $column, $key)
		: WherePart
		{
			$column      = $this->driver->escapeColumn($column,$this->scope->table);
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