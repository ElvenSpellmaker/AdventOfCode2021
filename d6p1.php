<?php

$fish = explode(",", rtrim(file_get_contents('d6.txt')));

$days = 80;
$newFish = [];
while ($days--)
{
	foreach ($fish as &$f)
	{
		$f--;
		if ($f < 0)
		{
			$f = 6;
			$newFish[] = 8;
		}
	}

	$fish = array_merge($fish, $newFish);
	$newFish = [];
	// echo join(', ', $fish), "\n";
}

echo count($fish), "\n";
