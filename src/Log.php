<?php

	namespace Traineratwot\PDOExtended;

	use DateTime;
	use Exception;
	use Monolog\Handler\StreamHandler;
	use Monolog\Logger;
	use Traineratwot\Cache\Cache;
	use Traineratwot\Cache\CacheException;
	use Traineratwot\config\Config;
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
		 */
		public function log(PDOE $PDOE, ?string $sql = '')
		: void
		{
			try {
				$this->PDOE = $PDOE;
				$where      = $this->find();
				$when       = (new DateTime())->format('Y-m-d H:i:s');
				$what       = preg_replace('@[\n\r]+@', ' ', $sql);
				$this->write($where, $when, $what);
			} catch (Exception $e) {
			}
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
				if (!str_contains($d['file'], 'PDOExtended\src') && !str_contains($d['file'], 'pdo-extended\src')) {
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
			$category = $this->PDOE->getKey();
			$logFile  = Config::get('CACHE_PATH', $category) . $category . DIRECTORY_SEPARATOR . 'PDOE.log';
			if (filesize($logFile) > 1024 * 1024 * 5) {
				unlink($logFile);
			}
			$logger = new Logger('PDOE');
			$logger->pushHandler(new StreamHandler($logFile, 100));
			$log = "$where -> \"$what\"";
			$logger->error($log);
			$logger->close();
		}

		/**
		 * @return string
		 */
		public function get()
		: string
		{
			try {
				return implode(PHP_EOL, Cache::getCache('LOG', $this->PDOE->getKey()));
			} catch (Exception $e) {
				return '';
			}
		}
	}