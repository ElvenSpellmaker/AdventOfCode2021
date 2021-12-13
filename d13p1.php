<?php

[$coords, $folds] = explode("\n\n", rtrim(file_get_contents('d13.txt')));

$coords = explode("\n", $coords);
$folds = explode("\n", $folds);

preg_match('%fold along (x|y)=(\d+)%', $folds[0], $matches);
[, $direction, $line] = $matches;

$grid = [];
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

	$grid["$y:$x"] = true;
}

echo count($grid), "\n";
