<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Delete extends Builder
	{
		public bool $isTruncate = FALSE;

		/**
		 * If set true, clear autoincrement
		 * @param bool $set
		 * @return void
		 */
		public function truncate(bool $set = TRUE)
		{
			$this->isTruncate = $set;
		}

		public function toSql()
		{
			$v = [];
			if ($this->where) {
				$w   = $this->where->get();
				$v   = $this->where->getValues();
				$sql = "DELETE FROM {$this->table} WHERE {$w}";
			} else {
				$sql = "DELETE FROM {$this->table}";
			}
			return Helpers::prepare($sql, $v, $this->driver->connection);
		}
	}