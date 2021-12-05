<?php

$vents = explode("\n", rtrim(file_get_contents('d5.txt')));

$map = [];
$crossMap = [];

foreach ($vents as $vent)
{
	preg_match('%(\d+),(\d+) -> (\d+),(\d+)%', $vent, $matches);

	$x1 = $matches[1];
	$y1 = $matches[2];
	$x2 = $matches[3];
	$y2 = $matches[4];

	if ($x1 > $x2 || $y1 > $y2)
	{
		$tmp1 = $x1;
		$x1 = $x2;
		$x2 = $tmp1;

		$tmp2 = $y1;
		$y1 = $y2;
		$y2 = $tmp2;
	}

	if ($x1 === $x2)
	{
		for ($i = $y1; $i <= $y2; $i++)
		{
			if (isset($map[$i][$x1]))
			{
				$crossMap["$i:$x1"] = true;
			}
			$map[$i][$x1] = true;
		}

		continue;
	}

	if ($y1 === $y2)
	{
		for ($i = $x1; $i <= $x2; $i++)
		{
			if (isset($map[$y1][$i]))
			{
				$crossMap["$y1:$i"] = true;
			}
			$map[$y1][$i] = true;
		}

		continue;
	}
}

// function drawMap(array $map, int $x, int $y)
// {
// 	for ($i = 0; $i <= $y; $i++)
// 	{
// 		for ($j = 0; $j <= $x; $j++)
// 		{
// 			echo isset($map[$i][$j]) ? '#' : '.';
// 		}

// 		echo "\n";
// 	}
// }

// drawMap($map, 9, 9);

// var_dump($crossMap);

echo count($crossMap), "\n";
