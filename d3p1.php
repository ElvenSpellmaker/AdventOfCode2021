<?php

$lines = explode("\n", rtrim(file_get_contents('d3.txt')));
/** @var string[][] $lines */
$lines = array_map('str_split', $lines);
$length = count($lines[0]) - 1;

$gamma = 0;
$epsilon = 0;
for ($i = 0; $i <= $length; $i++)
{
	$columnCount = array_count_values(array_column($lines, $i));
	arsort($columnCount);
	$gamma += array_key_first($columnCount) * 1 << $length - $i;
	$epsilon += array_key_last($columnCount) * 1 << $length - $i;
}

echo $gamma * $epsilon, "\n";
