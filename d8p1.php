<?php

$lines = explode("\n", rtrim(file_get_contents('d8.txt')));

$count = 0;
foreach ($lines as $line)
{
	[$input, $output] = explode(' | ', $line);
	preg_match_all('%[a-g]+%', $output, $matches);

	foreach ($matches[0] as $match)
	{
		$count += in_array(strlen($match), [2, 3, 4, 7]);
	}
}

echo $count, "\n";
