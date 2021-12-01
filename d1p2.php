<?php

$lines = explode("\n", file_get_contents('d1.txt'));

$count = 0;
$window = [];
$window[] = array_shift($lines);
$window[] = array_shift($lines);
$window[] = array_shift($lines);
foreach ($lines as $line)
{
	$count1 = array_sum($window);
	array_shift($window);
	$window[] = $line;
	$count2 = array_sum($window);
	if ($count2 > $count1)
	{
		$count++;
	}
}

echo $count, "\n";
