<?php

/**
 * @var string[][] $lines
 */
$lines = array_map('str_split', explode("\n", rtrim(file_get_contents('d10.txt'))));

$closingBrackets = [
	'(' => ')',
	'[' => ']',
	'{' => '}',
	'<' => '>',
];

$pointsMap = [
	')' => 1,
	']' => 2,
	'}' => 3,
	'>' => 4,
];

$autocompleteScores = [];
foreach ($lines as $line)
{
	$expected = [];

	foreach ($line as $char)
	{
		switch ($char)
		{
			case '(':
			case '[':
			case '{':
			case '<':
				$expected[] = $closingBrackets[$char];
			break;
			case ')':
			case ']':
			case '}':
			case '>':
				$expt = array_pop($expected);
				if ($expt !== $char)
				{
					continue 3;
				}
			break;
		}
	}

	$score = 0;
	for (end($expected); key($expected) !== null; prev($expected))
	{
		$current = current($expected);
		$score *= 5;
		$score += $pointsMap[$current];
	}

	$autocompleteScore[] = $score;
}

sort($autocompleteScore);
$middle = ((count($autocompleteScore) + 1) / 2 - 1);

echo $autocompleteScore[$middle], "\n";
