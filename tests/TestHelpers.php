<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Helpers;

	class TestHelpers extends TestCase
	{

		public function testPrepare()
		{
			$sql1 = "SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < :calories AND colour = :colour";
			$sql2 = 'SELECT "name", colour, calories FROM fruit WHERE calories < ? AND colour = ?';
			$sql3 = "SELECT * FROM issues WHERE tag::jsonb ?? ?";

			$sql1_ = Helpers::prepare($sql1, ['calories' => 150, 'colour' => 'red']);
			$sql2_ = Helpers::prepare($sql2, [150, 'red']);
			$sql3_ = Helpers::prepare($sql3, [150, 'red']);

			$this->assertEquals("SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < 150 AND colour = red;", $sql1_, 'test');
			$this->assertEquals('SELECT "name", colour, calories FROM fruit WHERE calories < 150 AND colour = red;', $sql2_, 'test');
			$this->assertEquals("SELECT * FROM issues WHERE tag::jsonb ?? 150;", $sql3_, 'test');
		}
	}
