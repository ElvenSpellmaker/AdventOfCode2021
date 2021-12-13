<?php

[$coords, $folds] = explode("\n\n", rtrim(file_get_contents('d13.txt')));

$coords = explode("\n", $coords);
$folds = explode("\n", $folds);

foreach ($folds as $fold)
{
	preg_match('%fold along (x|y)=(\d+)%', $fold, $matches);
	[, $direction, $line] = $matches;

	$newCoords = [];
	$grid = [];
	$highX = 0;
	$highY = 0;
	foreach ($coords as $coord)
	{
		preg_match('%(\d+),(\d+)%', $coord, $matches);
		[, $x, $y] = $matches;

		if ($direction === 'y')
		{
			if ($y > $line)
			{
				$distanceToLine = $y - $line;
				$y = $line - $distanceToLine;
			}
		}

		if ($direction === 'x')
		{
			if ($x > $line)
			{
				$distanceToLine = $x - $line;
				$x = $line - $distanceToLine;
			}
		}

		$newCoords[] = "$x,$y";
		$grid[$y][$x] = true;
		if ($x > $highX)
		{
			$highX = $x;
		}

		if ($y > $highY)
		{
			$highY = $y;
		}
	}

	$coords = $newCoords;
}

for ($y = 0; $y <= $highY; $y++)
{
	for ($x = 0; $x <= $highX; $x++)
	{
		echo isset($grid[$y][$x]) ? '#' : '.';
	}

	echo "\n";
}
