<?php

	namespace Traineratwot\PDOExtended;

	use PDO;

	class Helpers
	{

		/**
		 * @param string            $sql
		 * @param array             $values
		 * @param Callable|PDO|null $escape
		 * @param string            $end ';'
		 * @return string
		 */
		public static function prepare(string $sql, array $values, $escape = NULL, string $end = ';')
		: string
		{
			$sql      = trim(trim($sql), ';');
			$words    = preg_split(<<<REGEXP
@[\s\(\)\,\.\"\`\>\=\<\!\~\*\%\;\$\#\@']+@
REGEXP
				, $sql);
			$i        = -1;
			$nameTags = [];
			$question = [];
			foreach ($words as $key => $word) {
				if ($word === '?') {
					$i++;
					$question[$key] = $i;
					continue;
				}
				if (str_starts_with($word, ':')) {
					$nameTags[$key] = $word;
				}
			}
			foreach ($nameTags as $word) {
				if (array_key_exists($word, $values)) {
					$value = $values[$word];
				} elseif (array_key_exists(substr($word, 1), $values)) {
					$value = $values[substr($word, 1)];
				} else {
					continue;
				}
				$value = self::getValue($escape, $value, $word);
				$sql   = str_replace($word, $value, $sql);
			}
			foreach ($question as $i) {
				if (array_key_exists($i, $values)) {
					$value = $values[$i];
				} else {
					continue;
				}
				$value = self::getValue($escape, $value);
				$sql   = preg_replace('@([^?](\?)[^?])|([^?](\?)$)@', ' ' . $value . ' ', $sql, 1);
			}
			$sql = trim($sql);
			$sql .= $end;
			return preg_replace('/;+\s*$/', ';', $sql);
		}

		/**
		 * @param             $escape
		 * @param             $value
		 * @param string|null $key
		 * @return false|string
		 */
		public static function getValue($escape, $value, string $key = NULL)
		{
			if (is_null($escape)) {
				$value = "'" . escapeshellcmd($value) . "'";
			} elseif ($escape instanceof PDO) {
				$value = $escape->quote($value);
			} elseif (is_callable($escape)) {
				$value = $escape($value, $key);
			} else {
				$value = "'" . escapeshellcmd($value) . "'";
			}
			return $value;
		}

		/**
		 * @param string $string
		 * @param int    $flags
		 * @return void
		 */
		public static function warn(string $string, int $flags = E_USER_WARNING)
		{
			trigger_error($string, $flags);
		}

		/**
		 * @param $v
		 * @return mixed|string
		 */
		public static function strtolower($v)
		{
			if (is_string($v)) {
				return strtolower($v);
			}
			return $v;
		}

		public static function arrayToSqlIn($arr = [])
		{
			$dop = array_fill(0, count($arr), 256);
			foreach ($arr as $key => $value) {
				$arr[$key] = trim($value, "'");
			}
			return @implode(',', array_map('json_encode', $arr, $dop));
		}
	}