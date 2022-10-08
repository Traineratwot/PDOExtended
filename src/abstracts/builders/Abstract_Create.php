<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\Driver;
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
				$keys[] = $this->keyToSql($key, $value);
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
				return "PRIMARY KEY (`$value`) USING BTREE";
			}
			return '';
		}

		public function columnToSql(array $column)
		: string
		{
			$name    = $column['name'];
			$comment = $column['comment'];
			if ($comment) {
				$comment = "COMMENT '{$comment}'";
			}
			switch ($column['type']) {
				case TInt::class:
					$type   = "INT";
					$length = $column['options']['length'];
					if ($length > 11) {
						$type = "BIGINT";
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
					$c = <<<EOT
`$name` $type($length) {$unsigned} {$canBeBull} {$comment}
EOT;
					break;
				case TString::class:

					$type   = "VARCHAR";
					$length = $column['options']['length'];
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
					$c = <<<EOT
`$name` $type $canBeBull {$comment}
EOT;
					break;
			}
			return trim($c);
		}
	}