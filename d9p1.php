<?php

/**
 * @var array $locations
 */
$locations = array_map('str_split', explode("\n", rtrim(file_get_contents('d9.txt'))));

$rows = count($locations);
$columns = count($locations[0]);

function isLowPoint(string $height, int $x, int $y)
{
	global $locations, $rows, $columns;

	$top = $y - 1 < 0 ? INF : $locations[$y - 1][$x];
	$right = $x + 1 >= $columns ? INF : $locations[$y][$x + 1];
	$bottom = $y + 1 >= $rows ? INF : $locations[$y + 1][$x];
	$left = $x - 1 < 0 ? INF : $locations[$y][$x - 1];

	return $height < $top && $height < $right && $height < $bottom && $height < $left;
}

$lowPointsSum = 0;
for ($y = 0; $y < $rows; $y++)
{
	for ($x = 0; $x < $columns; $x++)
	{
		if (isLowPoint($locations[$y][$x], $x, $y))
		{
			$lowPointsSum += $locations[$y][$x] + 1;
		}
	}
}

echo $lowPointsSum, "\n";
