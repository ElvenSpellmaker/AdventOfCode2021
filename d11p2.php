<?php

/**
 * @var string[][] $octos
 */
$octos = array_map('str_split', explode("\n", rtrim(file_get_contents('d11.txt'))));

$rows = count($octos);
$columns = count($octos[0]);

function increaseNeighbour(int $y, int $x)
{
	global $octos;

	if (isset($octos[$y][$x]) && $octos[$y][$x] !== 0)
	{
		$octos[$y][$x]++;
	}
}

$steps = 0;
while (true)
{
	$flashCount = 0;
	$steps++;
	$seenFlash = [];

	for ($y = 0; $y < $rows; $y++)
	{
		for ($x = 0; $x < $rows; $x++)
		{
			$octos[$y][$x]++;
		}
	}

	do
	{
		foreach ($seenFlash as $k => [$y, $x])
		{
			// t
			increaseNeighbour($y - 1, $x);
			// tr
			increaseNeighbour($y - 1, $x + 1);
			// r
			increaseNeighbour($y, $x + 1);
			// br
			increaseNeighbour($y + 1, $x + 1);
			// b
			increaseNeighbour($y + 1, $x);
			// bl
			increaseNeighbour($y + 1, $x - 1);
			// l
			increaseNeighbour($y, $x - 1);
			// tl
			increaseNeighbour($y - 1, $x - 1);

			unset($seenFlash[$k]);
		}

		for ($y = 0; $y < $rows; $y++)
		{
			for ($x = 0; $x < $rows; $x++)
			{
				if ($octos[$y][$x] >= 10)
				{
					$octos[$y][$x] = 0;
					$seenFlash[] = [$y, $x];
					$flashCount++;
				}
			}
		}
	} while (count($seenFlash));


	if ($flashCount === $rows * $columns)
	{
		break;
	}
}

echo $steps, "\n";
