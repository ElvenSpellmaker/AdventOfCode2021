<?php

[$chain, $patterns] = explode("\n\n", rtrim(file_get_contents('d14.txt')));

$patterns = explode("\n", $patterns);

$templates = [];

foreach ($patterns as $pattern)
{
	preg_match('%([A-Z]+) -> ([A-Z])%', $pattern, $matches);
	$templates[$matches[1]] = $matches[2];
}

$map = [];
$count = [$chain[0] => 1];

for ($i = 0; $i < strlen($chain) - 1; $i++)
{
	$char = $chain[$i];
	$char2 = $chain[$i + 1];

	$count[$char2] = ($count[$char2] ?? 0) + 1;

	$map[$char . $char2] = ($map[$char . $char2] ?? 0) + 1;
}

$steps = 40;
while ($steps--)
{
	$newMap = [];
	foreach ($map as $combo => $comboCount)
	{
		$newSymbol = $templates[$combo];

		[$left, $right] = str_split($combo);

		$newMap[$left . $newSymbol] = ($newMap[$left . $newSymbol] ?? 0) + $comboCount;
		$newMap[$newSymbol . $right] = ($newMap[$newSymbol . $right] ?? 0) + $comboCount;

		$count[$newSymbol] = ($count[$newSymbol] ?? 0) + $comboCount;
	}

	$map = $newMap;
}

asort($count);

echo end($count) - reset($count), "\n";
