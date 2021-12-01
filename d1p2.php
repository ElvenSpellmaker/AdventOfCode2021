<?php

$lines = explode("\n", file_get_contents('d1.txt'));

$count = 0;
$linesCount = count($lines);
for ($i = 0, $j = 3; $j < $linesCount; $i++, $j++)
{
	$count += $lines[$i] < $lines[$j];
}

echo $count, "\n";
