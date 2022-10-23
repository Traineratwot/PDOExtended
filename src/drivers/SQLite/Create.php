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
				$canBeBull = "NOT NULL AUTOINCREMENT";
			}
			return <<<EOT
`$name` $type($length) {$canBeBull} {$comment} {$default}
EOT;
		}

		public function TFloat($column)
		: string
		{
			$name     = $column['name'];
			$default  = $column['default'];
			$type     = "DOUBLE";
			$canBeBull = $column['options']['canBeBull'];
			if ($canBeBull) {
				$canBeBull = "";
			} else {
				$canBeBull = "NOT NULL";
			}
			if ($column['options']['isPrimary']) {
				$canBeBull = "NOT NULL AUTOINCREMENT";
			}
			return <<<EOT
`$name` $type {$canBeBull} {$comment} {$default}
EOT;
		}
	}