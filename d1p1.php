<?php

$lines = explode("\n", file_get_contents('d1.txt'));

$count = 0;
$prev = INF;
foreach ($lines as $line)
{
	$count += $line > $prev;
	$prev = $line;
}

echo $count, "\n";
