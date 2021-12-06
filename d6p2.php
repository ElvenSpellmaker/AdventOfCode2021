<?php

ini_set('memory_limit', '3G');

$fish = explode(",", rtrim(file_get_contents('d6.txt')));
$fishFreq = array_count_values($fish);

$days = 256;
while ($days--)
{
	$newFishFreq = [];
	for ($i = 8; $i >= 0; $i--)
	{
		if (isset($fishFreq[$i]))
		{
			if ($i === 0)
			{
				$newFishFreq[6] = ($newFishFreq[6] ?? 0) + $fishFreq[$i];
				$newFishFreq[8] = $fishFreq[$i];
			}
			else
			{
				$newFishFreq[$i - 1] = $fishFreq[$i];
			}

		}
	}

	$fishFreq = $newFishFreq;
}

echo array_sum($fishFreq), "\n";
