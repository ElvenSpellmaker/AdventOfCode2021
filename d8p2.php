<?php

$lines = explode("\n", rtrim(file_get_contents('d8.txt')));

$count = 0;
foreach ($lines as $line)
{
	[$input, $output] = explode(' | ', $line);
	preg_match_all('%[a-g]+%', $input, $matches);
	preg_match_all('%[a-g]+%', $output, $outputs);

	$outputs = array_map('str_split', $outputs[0]);

	$two = null;
	$three = null;
	$five = null;
	$six = null;
	$nine = null;

	$one = null;
	$four = null;
	$seven = null;
	$eight = null;
	$sixes = [];
	$fives = [];

	foreach ($matches[0] as $match)
	{
		$splitMatch = str_split($match);
		sort($splitMatch);
		switch (strlen($match))
		{
			case 2:
				$one = $splitMatch;
			break;
			case 3:
				$seven = $splitMatch;
			break;
			case 4:
				$four = $splitMatch;
			break;
			case 5:
				$fives[] = $splitMatch;
			break;
			case 6:
				$sixes[] = $splitMatch;
			break;
			case 7:
				$eight = $splitMatch;
			break;
		}
	}

	// Top is easy just find the diff between seven and one
	$top = array_diff($seven, $one);
	$top = reset($top);

	// Using seven and four we can work out bottom
	// and bottomLeft
	$fourSeven = array_unique(array_merge($four, $seven));
	sort($fourSeven);
	$filteredSixes = [];
	foreach ($sixes as $lSix)
	{
		$filteredSixes[] = array_diff($lSix, $fourSeven);
	}

	usort($filteredSixes, function($a, $b) { return count($a) <=> count($b); });

	$bottom = reset($filteredSixes[0]);
	$bottomLeft = array_diff($filteredSixes[1], $filteredSixes[0]);
	$bottomLeft = reset($bottomLeft);

	// Using bottom and bottomLeft we can find out the rest
	// and top right using `2` which is in the fives
	foreach ($fives as $lFiveKey => $lFive)
	{
		if (! in_array($bottomLeft, $lFive))
		{
			continue;
		}

		$two = $lFive;
		unset($fives[$lFiveKey]);

		$middle = array_diff($two, [$top, $bottom, $bottomLeft], $one);
		$middle = reset($middle);

		$topLeft = array_diff($four, [$top, $bottom, $bottomLeft, $middle], $one);
		$topLeft = reset($topLeft);

		$topRight = array_diff($two, [$top, $middle, $bottomLeft, $bottom]);
		$topRight = reset($topRight);

		$bottomRight = array_diff($one, [$topRight]);
		$bottomRight = reset($bottomRight);

		// echo "top: $top, tl: $topLeft, middle: $middle, bottomLeft: $bottomLeft, bottom: $bottom, tr: $topRight, br: $bottomRight";exit;

		break;
	}

	$three = [$top, $topRight, $middle, $bottomRight, $bottom];
	$five = [$top, $topLeft, $middle, $bottomRight, $bottom];
	$six = [$top, $topLeft, $middle, $bottomLeft, $bottomRight, $bottom];
	$eight = [$top, $topLeft, $topRight, $middle, $bottomLeft, $bottomRight, $bottom];
	$nine = [$top, $topLeft, $topRight, $middle, $bottomRight, $bottom];
	$zero = [$top, $topLeft, $topRight, $bottomLeft, $bottomRight, $bottom];
	sort($three);
	sort($five);
	sort($six);
	sort($eight);
	sort($nine);
	sort($zero);

	$characterMap = [
		join(':', $zero) => 0,
		join(':', $one) => 1,
		join(':', $two) => 2,
		join(':', $three) => 3,
		join(':', $four) => 4,
		join(':', $five) => 5,
		join(':', $six) => 6,
		join(':', $seven) => 7,
		join(':', $eight) => 8,
		join(':', $nine) => 9,
	];

	$sumNumber = '';
	foreach ($outputs as $output)
	{
		sort($output);
		$sumNumber .= $characterMap[join(':', $output)];
	}

	$count += $sumNumber;
}

echo $count, "\n";
