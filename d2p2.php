<?php

$lines = explode("\n", file_get_contents('d2.txt'));
$pos = 0;
$depth = 0;
$aim = 0;

foreach ($lines as $line)
{
	$split = explode(' ', $line);
	switch($split[0])
	{
		case 'forward':
			$pos += $split[1];
			$depth += $aim * $split[1];
		break;
		case 'up':
			$aim -= $split[1];
		break;
		case 'down':
			$aim += $split[1];
		break;
	}
}

echo $pos * $depth, "\n";
