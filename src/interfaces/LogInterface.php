<?php

	namespace Traineratwot\PDOExtended\interfaces;

	use Traineratwot\PDOExtended\PDOE;

	interface LogInterface
	{
		/**
		 * return
		 * @return static
		 */
		public static function init()
		: self;

		public function log(string $sql, PDOE $PDOE)
		: void;

		public function get()
		: string;
	}