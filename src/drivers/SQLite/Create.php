<?php

	namespace Traineratwot\PDOExtended\drivers\SQLite;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Create;

	class Create extends Abstract_Create
	{
		public function toSql()
		{
			$table_name = $this->driver->escapeTable($this->scope->table);
			$COMMENT    = '';
			$COLLATE    = '';
			$ENGINE     = '';
			if ($this->scope->comment) {
				$COMMENT = "COMMENT='{$this->scope->comment}'";
			}
			if ($this->scope->collate) {
				$COLLATE = "COLLATE={$this->scope->collate}";
			}
			if ($this->scope->engine) {
				$ENGINE = "ENGINE={$this->scope->engine}";
			}
			$columns = [];
			$keys    = [];
			foreach ($this->scope->columns as $column) {
				$columns[] = $this->columnToSql($column);
			}
			foreach ($this->scope->keys as $key => $value) {
				if ($key !== 'primary') {
					foreach ($value as $k => $val) {
						if ($key = $this->keyToSql($key, $val)) {
							$keys[] = $key;
						}
					}
				} elseif ($key = $this->keyToSql($key, $val)) {
					$keys[] = $key;
				}
			}
			$body      = array_merge($columns, $keys);
			$body      = implode(",\n\t", $body);
			$dropTable = '';
			if ($this->scope->dropTable) {
				$dropTable = "DROP TABLE IF EXISTS $table_name;";
			}
			return <<<SQL
{$dropTable}
CREATE TABLE IF NOT EXISTS $table_name (
	{$body}
)
;
SQL;
		}

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
`$name` $type {$canBeBull} {$default}
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
`$name` $type {$canBeBull} {$default}
EOT;
		}

		public function TString($column)
		: string
		{
			$name    = $column['name'];
			$default = $column['default'];
			$comment = $column['comment'];
			$type    = "TEXT";
			$length  = $column['options']['length'];
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			return <<<EOT
`$name` $type $canBeBull {$comment} {$default}
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