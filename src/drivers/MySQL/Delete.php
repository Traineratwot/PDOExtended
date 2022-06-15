<?php

	namespace Traineratwot\PDOExtended\drivers\MySQL;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Delete;
	use Traineratwot\PDOExtended\Helpers;

	class Delete extends Abstract_Delete
	{

		/**
		 * @return string
		 */
		public function toSql()
		{
			$v = [];
			if ($this->where) {
				$w   = $this->where->get($this->validators);
				$v   = $this->where->getValues();
				$sql = "DELETE FROM {$this->table} WHERE {$w}";
			} else {
				$sql = "DELETE FROM {$this->table};";
			}
			if ($this->isTruncate) {
				$sql .= "ALTER TABLE {$this->table} AUTO_INCREMENT=0;";
			}
			return Helpers::prepare($sql, $v, function ($val, $key) {
				if ($this->validators[trim($key, ':')]) {
					return $this->validators[trim($key, ':')]->escape($this->driver->connection, $val);
				}
				return Helpers::getValue($this->driver->connection, $val);
			});
		}
	}