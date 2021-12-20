<?php

preg_match('%target area: x=(-?\d+)..(-?\d+), y=(-?\d+)..(-?\d+)%', file_get_contents('d17.txt'), $matches);

[, $minX, $maxX, $minY, $maxY] = $matches;

$maxInitialY = abs($minY) - 1;
$minInitialY = $minY;

$yForStepsTaken = [];
$highestSteps = 0;

for ($y = $maxInitialY; $y >= $minInitialY; $y--)
{
	$steps = 1;
	$currY = $y;
	$currYVec = $y;
	while ($currY >= $minInitialY)
	{
		if ($currY <= $maxY && $currY >= $minY)
		{
			$yForStepsTaken[$steps][] = $y;

			if ($steps > $highestSteps)
			{
				$highestSteps = $steps;
			}
		}

		$currY = $currY + --$currYVec;
		$steps++;
	}
}

$minXTraj = null;
for ($i = 0;; $i++)
{
	$totalI = ($i ** 2 + $i) / 2;

	if ($totalI >= $minX && $minXTraj === null)
	{
		$minXTraj = $i;
		break;
	}
}

$viableTrajectories = [];

for ($x = $minXTraj; $x <= $maxX; $x++)
{
	$steps = 1;
	$currX = $x;
	$currXVec = $x - 1;

	do
	{
		if ($currX >= $minX && $currX <= $maxX)
		{
			foreach ($yForStepsTaken[$steps] as $y)
			{
				$viableTrajectories["$x:$y"] = true;
			}
		}

		$currX += $currXVec--;

		$steps++;

		if ($currX > $maxX)
		{
			break;
		}
	}
	while($currXVec !== 0);

	// Keep going dude, more Y values to consider...
	if ($currXVec === 0)
	{
		while ($steps <= $highestSteps)
		{
			if (isset($yForStepsTaken[$steps]))
			{
				foreach ($yForStepsTaken[$steps] as $y)
				{
					$viableTrajectories["$x:$y"] = true;
				}
			}

			$steps++;
		}
	}
}

echo count($viableTrajectories), "\n";
