<?php

	namespace Traineratwot\PDOExtended;

	class Helpers
	{

		/**
		 * @param string   $sql
		 * @param array    $values
		 * @param Callable $escape
		 * @return string
		 */
		public static function prepare($sql, $values, $escape = NULL)
		{
			$words = preg_split('@\s+@', $sql);
			$i     = -1;
			$a     = [];
			foreach ($words as $key => $word) {
				if ($word === '?') {
					$i++;
					$a[$key] = $i;
					continue;
				}
				if (strpos($word, ':') === 0) {
					$a[$key] = substr($word, 1);
				}
			}
			foreach ($a as $wordPos => $key) {
				if (array_key_exists($key, $values) || array_key_exists(':' . $key, $values)) {
					//todo функция эрнирования
					if (is_callable($escape)) {
						$words[$wordPos] = $escape($values[$key]);
					} else {
						$words[$wordPos] = $values[$key];
					}
				}
			}
			$sql    = implode(' ', $words);
			$search = ';';
			if (substr($sql, strlen($sql) - strlen($search)) !== $search) {
				$sql .= ';';
			}
			return $sql;
		}
	}