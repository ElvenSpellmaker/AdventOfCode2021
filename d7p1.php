<?php

$crabs = explode(',', rtrim(file_get_contents('d7.txt')));

$positionCost = [];

$min = min($crabs);
$max = max($crabs);

for ($i = $min; $i <= $max; $i++)
{
	$cost = 0;
	array_walk($crabs, function($crab) use ($i, &$cost) {
		$cost += abs($crab - $i);
	});

	$positionCost[$i] = $cost;
}

asort($positionCost);

echo reset($positionCost), "\n";
