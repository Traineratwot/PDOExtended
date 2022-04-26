<?php

	use Traineratwot\PDOExtended\Helpers;

	require __DIR__. '/vendor/autoload.php';


	echo '<pre>';
	var_dump(Helpers::prepare($sql1, ['calories' => 150, 'colour' => 'red'])			);
	var_dump(Helpers::prepare($sql2, [150, 'red'])										);
	var_dump(Helpers::prepare($sql3, [150, 'red'])										);
	die;
