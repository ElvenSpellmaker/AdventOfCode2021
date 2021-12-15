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

class Node
{
	public $x;

	public $y;

	public function __construct(int $y, int $x)
	{
		$this->y = $y;
		$this->x = $x;
	}

	public function getNeighbours()
	{
		global $squares;

		$neighbours = [];
		// top
		if (isset($squares[$this->y - 1][$this->x]))
		{
			$neighbours[] = [
				'y' => $this->y - 1,
				'x' => $this->x,
				'dist' => $squares[$this->y - 1][$this->x],
			];
		}

		// right
		if (isset($squares[$this->y][$this->x + 1]))
		{
			$neighbours[] = [
				'y' => $this->y,
				'x' => $this->x + 1,
				'dist' => $squares[$this->y][$this->x + 1],
			];
		}

		// bottom
		if (isset($squares[$this->y + 1][$this->x]))
		{
			$neighbours[] = [
				'y' => $this->y + 1,
				'x' => $this->x,
				'dist' => $squares[$this->y + 1][$this->x],
			];
		}

		// left
		if (isset($squares[$this->y][$this->x - 1]))
		{
			$neighbours[] = [
				'y' => $this->y,
				'x' => $this->x - 1,
				'dist' => $squares[$this->y][$this->x - 1],
			];
		}

		return $neighbours;
	}
}

$dist['0:0'] = 0;
$q->insert(new Node(0, 0), -$dist['0:0']);

$maxU = ($rows - 1) . ':' . ($columns - 1);

$i = 0;
while (count($q))
{
	/**
	 * @var Node $uNode
	 */
	$uNode = $q->extract();

	$uNodeString = $uNode->y . ':' . $uNode->x;

	if ($uNodeString === $maxU)
	{
		break;
	}

	$y = $uNode->y;
	$x = $uNode->x;
	foreach ($uNode->getNeighbours() as $neighbour)
	{
		$alt = $dist[$y . ':' . $x] + $neighbour['dist'];
		if ($alt < ($dist[$neighbour['y'] . ':' . $neighbour['x']] ?? INF))
		{
			$dist[$neighbour['y'] . ':' . $neighbour['x']] = $alt;
			$prev[$neighbour['y'] . ':' . $neighbour['x']] = $uNodeString;
			$q->insert(new Node($neighbour['y'], $neighbour['x']), -$alt);
		}
	}
}

$sum = 0;
$u = $maxU;
while ($u !== null && $u !== '0:0')
{
	[$y, $x] = explode(':', $u);
	$sum += $squares[$y][$x];

	$u = $prev[$u] ?? null;
}

echo $sum, "\n";
