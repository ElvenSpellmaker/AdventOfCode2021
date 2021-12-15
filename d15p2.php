<?php

/**
 * @var string[][] $squares
 */
$squares = array_map('str_split', explode("\n", rtrim(file_get_contents('d15.txt'))));

$rows = count($squares);
$columns = count($squares[0]);

$q = new SplPriorityQueue;
$dist = [];
$prev = [];

$newSquares[0] = $squares;

for ($i = 1; $i <= 8; $i++)
{
	for ($y = 0; $y < $rows; $y++)
	{
		for ($x = 0; $x < $columns; $x++)
		{
			$newSquares[$i][$y][$x] = ($squares[$y][$x] + $i - 1) % 9 + 1;
		}
	}
}

// Initial
$newMap = [0 => [0 => $squares]];

// Plus one
$newMap[0][1] = $newSquares[1];
$newMap[1][0] = $newSquares[1];

// Plus Two
$newMap[0][2] = $newSquares[2];
$newMap[1][1] = $newSquares[2];
$newMap[2][0] = $newSquares[2];

// Plus three
$newMap[0][3] = $newSquares[3];
$newMap[1][2] = $newSquares[3];
$newMap[2][1] = $newSquares[3];
$newMap[3][0] = $newSquares[3];

// Plus 4
$newMap[0][4] = $newSquares[4];
$newMap[1][3] = $newSquares[4];
$newMap[2][2] = $newSquares[4];
$newMap[3][1] = $newSquares[4];
$newMap[4][0] = $newSquares[4];

// Plus 5
$newMap[1][4] = $newSquares[5];
$newMap[2][3] = $newSquares[5];
$newMap[3][2] = $newSquares[5];
$newMap[4][1] = $newSquares[5];

// Plus 6
$newMap[2][4] = $newSquares[6];
$newMap[3][3] = $newSquares[6];
$newMap[4][2] = $newSquares[6];

// Plus 7
$newMap[3][4] = $newSquares[7];
$newMap[4][3] = $newSquares[7];

// Plus 8
$newMap[4][4] = $newSquares[8];

// for ($mY = 0; $mY < count($newMap); $mY++)
// {
// 	for ($y = 0; $y < $rows; $y++)
// 	{
// 		for ($mX = 0; $mX < count($newMap[0]); $mX++)
// 		{
// 			for ($x = 0; $x < $columns; $x++)
// 			{
// 				echo $newMap[$mY][$mX][$y][$x];
// 			}
// 			echo ":";
// 		}

// 		exit;
// 	}
// }

class Node
{
	public $x;

	public $y;

	public $mX;

	public $mY;

	public function __construct(int $y, int $x, int $mY, int $mX)
	{
		$this->y = $y;
		$this->x = $x;
		$this->mY = $mY;
		$this->mX = $mX;
	}

	public function getNeighbours()
	{
		global $newMap, $rows, $columns;

		$rowKey = $rows - 1;
		$columnKey = $columns - 1;

		$currMY = $this->mY;
		$currMX = $this->mX;

		$neighbours = [];
		// top
		$y = $this->y - 1 < 0 ? $rowKey : $this->y - 1;
		$currMY = $this->y - 1 < 0 ? $currMY - 1 : $currMY;
		if (isset($newMap[$currMY][$currMX][$y][$this->x]))
		{
			$neighbours[] = [
				'y' => $y,
				'x' => $this->x,
				'mY' => $currMY,
				'mX' => $currMX,
				'dist' => $newMap[$currMY][$currMX][$y][$this->x],
			];
		}

		$currMY = $this->mY;

		// right
		$x = $this->x + 1 > $columnKey ? 0 : $this->x + 1;
		$currMX = $this->x + 1 > $columnKey ? $currMX + 1 : $currMX;
		if (isset($newMap[$currMY][$currMX][$this->y][$x]))
		{
			$neighbours[] = [
				'y' => $this->y,
				'x' => $x,
				'mY' => $currMY,
				'mX' => $currMX,
				'dist' => $newMap[$currMY][$currMX][$this->y][$x],
			];
		}

		$currMX = $this->mX;

		// bottom
		$y = $this->y + 1 > $rowKey ? 0 : $this->y + 1;
		$currMY = $this->y + 1 > $rowKey ? $currMY + 1 : $currMY;
		if (isset($newMap[$currMY][$currMX][$y][$this->x]))
		{
			$neighbours[] = [
				'y' => $y,
				'x' => $this->x,
				'mY' => $currMY,
				'mX' => $currMX,
				'dist' => $newMap[$currMY][$currMX][$y][$this->x],
			];
		}

		$currMY = $this->mY;

		// left
		$x = $this->x - 1 < 0 ? $columnKey : $this->x - 1;
		$currMX = $this->x - 1 < 0 ? $currMX - 1 : $currMX;
		if (isset($newMap[$currMY][$currMX][$this->y][$x]))
		{
			$neighbours[] = [
				'y' => $this->y,
				'x' => $x,
				'mY' => $currMY,
				'mX' => $currMX,
				'dist' => $newMap[$currMY][$currMX][$this->y][$x],
			];
		}

		return $neighbours;
	}
}

$dist['0:0-0:0'] = 0;
$q->insert(new Node(0, 0, 0, 0), -$dist['0:0-0:0']);

$maxU = "4:4-" . ($rows - 1) . ':' . ($columns - 1);

$i = 0;
while (count($q))
{
	/**
	 * @var Node $uNode
	 */
	$uNode = $q->extract();

	$y = $uNode->y;
	$x = $uNode->x;
	$mY = $uNode->mY;
	$mX = $uNode->mX;

	$uNodeString = $mY . ':' . $mX . '-' . $y . ':' . $x;

	if ($uNodeString === $maxU)
	{
		break;
	}

	foreach ($uNode->getNeighbours() as $neighbour)
	{
		$alt = $dist["$mY:$mX-" . $y . ':' . $x] + $neighbour['dist'];
		if ($alt < ($dist[$neighbour['mY'] . ':' . $neighbour['mX'] . '-' . $neighbour['y'] . ':' . $neighbour['x']] ?? INF))
		{
			$dist[$neighbour['mY'] . ':' . $neighbour['mX'] . '-' . $neighbour['y'] . ':' . $neighbour['x']] = $alt;
			$prev[$neighbour['mY'] . ':' . $neighbour['mX'] . '-' . $neighbour['y'] . ':' . $neighbour['x']] = $uNodeString;
			$q->insert(new Node($neighbour['y'], $neighbour['x'], $neighbour['mY'], $neighbour['mX']), -$alt);
		}
	}
}

$sum = 0;
$u = $maxU;
while ($u !== null && $u !== '0:0-0:0')
{
	[$mapSegment, $cellSegment] = explode('-', $u);
	[$mY, $mX] = explode(':', $mapSegment);
	[$y, $x] = explode(':', $cellSegment);
	$sum += $newMap[$mY][$mX][$y][$x];

	// echo $u, "\n";

	$u = $prev[$u] ?? null;
}

echo $sum, "\n";
