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

		public function log(PDOE $PDOE, ?string $sql = '')
		: void;

		public function get()
		: string;
	}