<?php

$lines = explode("\n", file_get_contents('d1.txt'));

$count = 0;
$prev = 0;
foreach ($lines as $line)
{
	if ($prev !== 0 && $line > $prev)
	{
		$count++;
	}
	$prev = $line;
}

echo $count, "\n";
