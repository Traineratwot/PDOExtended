<?php

	namespace Traineratwot\PDOExtended;

	use DateTime;
	use Traineratwot\Cache\Cache;
	use Traineratwot\Cache\CacheException;
	use Traineratwot\PDOExtended\interfaces\LogInterface;

	class Log implements LogInterface
	{
		public int    $limit = 100;
		private ?PDOE $PDOE;

		/**
		 * @return Log
		 */
		public static function init()
		: Log
		{
			$var = 'PDOE_LOG';
			global $$var;
			if (!isset($$var) || is_null($$var)) {
				$$var = new self();
			}
			return $$var;
		}

		/**
		 * @param PDOE        $PDOE
		 * @param string|null $sql
		 * @return void
		 * @throws CacheException
		 */
		public function log(PDOE $PDOE, ?string $sql = '')
		: void
		{
			$this->PDOE = $PDOE;
			$where      = $this->find();
			$when       = (new DateTime())->format('Y-m-d H:i:s');
			$what       = preg_replace('@[\n\r]+@', ' ', $sql);
			$this->write($where, $when, $what);
		}

		/**
		 * @return string
		 */
		private function find()
		{
			$debug = debug_backtrace();
			$d     = NULL;
			foreach ($debug as $d) {
				$d['file'] = strtr($d['file'], [
					'/' => '\\',
				]);
				if (strpos($d['file'], 'PDOExtended\src') === FALSE && strpos($d['file'], 'pdo-extended\src') === FALSE) {
					break;
				}
			}
			return $d['file'] . ':' . $d['line'];
		}

		/**
		 * @param $where
		 * @param $when
		 * @param $what
		 * @return void
		 * @throws CacheException
		 */
		private function write($where, $when, $what)
		{
			$log      = "$where [$when]: $what";
			$oldLog   = Cache::getCache('LOG', $this->PDOE->getKey()) ?: [];
			$oldLog[] = $log;
			$newLog   = array_slice($oldLog, $this->limit * -1, $this->limit, TRUE);
			Cache::setCache('LOG', $newLog, 0, $this->PDOE->getKey());
		}

		/**
		 * @return string
		 */
		public function get()
		: string
		{
			return implode(PHP_EOL, Cache::getCache('LOG', $this->PDOE->getKey()));
		}
	}