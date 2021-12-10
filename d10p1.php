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

$scores = [
	')' => 3,
	']' => 57,
	'}' => 1197,
	'>' => 25137,
];

$syntaxScore = 0;
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
					$syntaxScore += $scores[$char];
					continue 3;
				}
			break;
		}
	}
}

echo $syntaxScore, "\n";
