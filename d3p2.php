<?php

$lines = explode("\n", rtrim(file_get_contents('d3.txt')));
/** @var string[][] $lines */
$lines = array_map('str_split', $lines);
$oxygenLines = $lines;
$co2Lines = $lines;
$length = count($lines[0]) - 1;

$getOxygenBit = function ($leastCommon, $mostCommon, $columnCount) { return $leastCommon === $mostCommon ? '1' : (string)array_key_first($columnCount); };
$getCo2ScrubberBit = function ($leastCommon, $mostCommon, $columnCount) { return $leastCommon === $mostCommon ? '0' : (string)array_key_last($columnCount); };

function filterLines(array &$lines, int $i, Closure $getBit)
{
	if (count($lines) < 2)
	{
		return;
	}

	$columnCount = array_count_values(array_column($lines, $i));
	arsort($columnCount);

	$leastCommon = end($columnCount);
	$mostCommon = reset($columnCount);

	$bit = $getBit($leastCommon, $mostCommon, $columnCount);
	$lines = array_filter($lines, function($line) use ($i, $bit) { return $line[$i] === $bit; });

	// $fn = function($l) { return join('', $l); };
	// echo "Turn $i - Bit: $bit, LC: $leastCommon, MC: $mostCommon: ", join(", ", array_map($fn, $lines)), "\n";
}

for ($i = 0; $i <= $length; $i++)
{
	filterLines($oxygenLines, $i, $getOxygenBit);
	filterLines($co2Lines, $i, $getCo2ScrubberBit);

	if (count($oxygenLines) === 1 && count($co2Lines) === 1)
	{
		break;
	}
}

if (count($oxygenLines) !== 1 || count($co2Lines) !== 1)
{
	echo "Whoops something went wrong!\n";
	exit(1);
}

$oxygenGeneratorRating = bindec(join('', reset($oxygenLines)));
$co2ScrubberRating = bindec(join('', reset($co2Lines)));

echo $oxygenGeneratorRating * $co2ScrubberRating, "\n";
