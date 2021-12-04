<?php

$lines = explode("\n", file_get_contents('d4.txt'));

$callings = explode(',', array_shift($lines));
array_shift($lines);

$winnerSum = 0;

require 'd4-helper.php';

$bingoSquares = [];
$i = 0;
$lineCount = count($lines);
while($i < $lineCount)
{
	$bingoSquare = new BingoSquare($winnerSum);
	while ($lines[$i] !== '')
	{
		$row = preg_split('% +%', $lines[$i], -1, PREG_SPLIT_NO_EMPTY);
		$bingoSquare->addValues($row);
		$i++;
	}

	$bingoSquares[] = $bingoSquare;
	$i++;
}

foreach ($callings as $calling)
{
	foreach ($bingoSquares as $bingoSquare)
	{
		$bingoSquare->markOffNumber($calling);

		if ($winnerSum !== 0)
		{
			echo $winnerSum * $calling, "\n";
			exit;
		}
	}
}

echo "Whooops something went wrong!\n";
exit(1);
