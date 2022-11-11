<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\tableInfo\dataType\TEnum;
	use Traineratwot\PDOExtended\tableInfo\dataType\TInt;
	use Traineratwot\PDOExtended\tableInfo\dataType\TString;
	use Traineratwot\PDOExtended\tableInfo\PDOENewDbObject;

	abstract class Abstract_Create
	{
		public PDOENewDbObject $scope;
		public ?Driver         $driver = NULL;

		public function __construct(PDOENewDbObject $scope)
		{
			$this->scope  = $scope;
			$this->driver = $scope->driver;
		}

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
{$COMMENT}
{$COLLATE}
{$ENGINE}
;
SQL;
		}

		public function keyToSql($key, $value)
		: string
		{
			if ($key === 'primary') {
				if (!is_array($value)) {
					$value = [$value];
				}
				$columns = implode(',', array_map(function ($column) {
					if ($column) {
						return $this->driver->escapeColumn($column);
					}
					return NULL;
				}, $value));
				return "PRIMARY KEY ($columns) USING BTREE";
			}
			if ($key === 'unique') {
				if (is_string($value)) {
					$value = $this->driver->escapeColumn($value);
					return "UNIQUE INDEX $value ($value)";
				}
				if (is_array($value)) {
					$key_name = $this->driver->escapeColumn(implode('_', $value));
					$columns  = implode(',', array_map(function ($column) {
						if ($column) {
							return $this->driver->escapeColumn($column);
						}
						return NULL;
					}, $value));
					return "UNIQUE INDEX $key_name ($columns)";
				}
			}
			return '';
		}

		public function columnToSql(array $column)
		: string
		{
			$comment = $column['comment'];
			if ($comment) {
				$comment = "COMMENT '{$comment}'";
			}
			if (is_null($default)) {
				$default = "";
			} else {
				$default = "DEFAULT " . Helpers::getValue(NULL, $default);
			}
			switch ($column['type']) {
				case TInt::class:
					$c = $this->TInt($column);
					break;
				case TString::class:
					$c = $this->TString($column);
					break;
				case TEnum::class:
					$c = $this->TEnum($column);
					break;
			}
			return trim($c);
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
			$unsigned = $column['options']['unsigned'];
			if ($unsigned) {
				$unsigned = "UNSIGNED";
			} else {
				$unsigned = "";
			}
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			if ($column['options']['isPrimary']) {
				$canBeBull = "NOT NULL AUTO_INCREMENT";
			}
			return <<<EOT
`$name` $type($length) {$unsigned} {$canBeBull} {$comment} {$default}
EOT;
		}

		public function TFloat($column)
		: string
		{
			$name     = $column['name'];
			$default  = $column['default'];
			$type     = "DOUBLE";
			$unsigned = $column['options']['unsigned'];
			if ($unsigned) {
				$unsigned = "UNSIGNED";
			} else {
				$unsigned = "";
			}
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			if ($column['options']['isPrimary']) {
				$canBeBull = "NOT NULL AUTO_INCREMENT";
			}
			return <<<EOT
`$name` $type {$unsigned} {$canBeBull} {$comment} {$default}
EOT;
		}

		public function TString($column)
		: string
		{
			$name    = $column['name'];
			$default = $column['default'];
			$type    = "VARCHAR";
			$length  = $column['options']['length'];
			if ($length > 256 || $length === 0) {
				$type = "LONGTEXT";
			}
			if ($length > 0) {
				$type = "$type($length)";
			}
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

		public function TEnum($column)
		: string
		{
			$name      = $column['name'];
			$default   = $column['default'];
			$cases     = $column['options']['cases'];
			$cases     = Helpers::arrayToSqlIn($cases);
			$type      = "ENUM($cases)";
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
	}