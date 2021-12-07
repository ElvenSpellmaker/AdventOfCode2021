<?php

$crabs = explode(',', rtrim(file_get_contents('d7.txt')));

$positionCost = [];
$sumMap = [];

$min = min($crabs);
$max = max($crabs);

for ($i = $min; $i <= $max; $i++)
{
	$cost = 0;
	array_walk($crabs, function($crab) use ($i, &$cost, &$sumMap) {
		$move = abs($crab - $i);
		if (! isset($sumMap[$move]))
		{
			$posCost = array_sum(range(1, $move));
			$sumMap[$move] = $posCost;
		}

		// This is the minimum solution, adding just two functions, but it's slow
		// hence the caching code above.
		// $cost += array_sum(range(1, abs($crab - $i)));

		$cost += $sumMap[$move];
	});

	$positionCost[$i] = $cost;
}

asort($positionCost);

echo reset($positionCost), "\n";
