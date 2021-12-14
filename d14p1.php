<?php

[$chain, $patterns] = explode("\n\n", rtrim(file_get_contents('d14.txt')));

$patterns = explode("\n", $patterns);

$templates = [];

foreach ($patterns as $pattern)
{
	preg_match('%([A-Z]+) -> ([A-Z])%', $pattern, $matches);
	$templates[$matches[1]] = $matches[2];
}

$steps = 10;
while ($steps--)
{
	$newChain = $chain[0];
	$count = [$chain[0] => 1];
	for ($i = 0; $i < strlen($chain) - 1; $i++)
	{
		$char = $chain[$i];
		$char2 = $chain[$i + 1];

		$count[$char2] = ($count[$char2] ?? 0) + 1;
		$count[$templates[$char . $char2]] = ($count[$templates[$char . $char2]] ?? 0) + 1;

		$newChain .= $templates[$char . $char2] . $char2;
	}

	$chain = $newChain;
}

asort($count);

echo end($count) - reset($count), "\n";
