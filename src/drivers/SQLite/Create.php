<?php

	namespace Traineratwot\PDOExtended\drivers\SQLite;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Create;

	class Create extends Abstract_Create
	{
		public function TInt($column)
		: string
		{
			$name    = $column['name'];
			$default = $column['default'];
			$type    = "INT";
			$length  = $column['options']['length'];
			if ($length > 11) {
				$type = "BIGINT";
			}
			if ($length <= 3) {
				$type = "TINYINT";
			}
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			if ($column['options']['isPrimary']) {
				$type      = 'INTEGER';
				$canBeBull = "PRIMARY KEY AUTOINCREMENT";
			}
			return <<<EOT
`$name` $type {$canBeBull} {$comment} {$default}
EOT;
		}

		public function TFloat($column)
		: string
		{
			$name      = $column['name'];
			$default   = $column['default'];
			$type      = "DOUBLE";
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			if ($column['options']['isPrimary']) {
				$canBeBull = "PRIMARY KEY AUTOINCREMENT";
			}
			return <<<EOT
`$name` $type {$canBeBull} {$comment} {$default}
EOT;
		}

		public function keyToSql($key, $value)
		: string
		{
			if ($key === 'primary') {
				return '';
			}
			if ($key === 'unique') {
				if (is_string($value)) {
					$value = $this->driver->escapeColumn($value);
					return "UNIQUE INDEX $value ($value)";
				}
				if (is_array($value)) {
					$key_name = $this->driver->escapeColumn(implode('_', $value));
					$columns  = implode(',', array_map(function ($column) {
						return $this->driver->escapeColumn($column);
					}, $value));
					return "UNIQUE INDEX $key_name ($columns)";
				}
			}
			return '';
		}
	}