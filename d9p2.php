<?php

/**
 * @var array $locations
 */
$locations = array_map('str_split', explode("\n", rtrim(file_get_contents('d9.txt'))));

$rows = count($locations);
$columns = count($locations[0]);

$basins = new SplMinHeap;

function isLowPoint(string $height, int $x, int $y)
{
	global $locations, $rows, $columns;

	$top = $y - 1 < 0 ? INF : $locations[$y - 1][$x];
	$right = $x + 1 >= $columns ? INF : $locations[$y][$x + 1];
	$bottom = $y + 1 >= $rows ? INF : $locations[$y + 1][$x];
	$left = $x - 1 < 0 ? INF : $locations[$y][$x - 1];

	return $height < $top && $height < $right && $height < $bottom && $height < $left;
}

function findBasinSize(string $height, int $x, int $y, array &$seenHeights = [])
{
	global $locations, $rows, $columns;

	$size = 1;

	if ($height === '9')
	{
		return 0;
	}

	$top = $y - 1 < 0 ? '9' : $locations[$y - 1][$x];
	$right = $x + 1 >= $columns ? '9' : $locations[$y][$x + 1];
	$bottom = $y + 1 >= $rows ? '9' : $locations[$y + 1][$x];
	$left = $x - 1 < 0 ? '9' : $locations[$y][$x - 1];

	if (! isset($seenHeights[$y - 1][$x]))
	{
		$seenHeights[$y - 1][$x] = true;
		$size += findBasinSize($top, $x, $y - 1, $seenHeights);
	}

	if (! isset($seenHeights[$y][$x + 1]))
	{
		$seenHeights[$y][$x + 1] = true;
		$size += findBasinSize($right, $x + 1, $y, $seenHeights);
	}

	if (! isset($seenHeights[$y + 1][$x]))
	{
		$seenHeights[$y + 1][$x] = true;
		$size += findBasinSize($bottom, $x, $y + 1, $seenHeights);
	}

	if (! isset($seenHeights[$y][$x - 1]))
	{
		$seenHeights[$y][$x - 1] = true;
		$size += findBasinSize($left, $x - 1, $y, $seenHeights);
	}

	return $size;
}

for ($y = 0; $y < $rows; $y++)
{
	for ($x = 0; $x < $columns; $x++)
	{
		if (isLowPoint($locations[$y][$x], $x, $y))
		{
			$seenHeights = [];
			$seenHeights[$y][$x] = true;
			$basins->insert(findBasinSize($locations[$y][$x], $x, $y, $seenHeights));
			if ($basins->count() > 3)
			{
				$basins->extract();
			}
		}
	}
}

echo array_product(iterator_to_array($basins)), "\n";
