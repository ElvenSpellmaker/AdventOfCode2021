<?php

require 'vendor/autoload.php';

$snails = explode("\n", rtrim(file_get_contents('d18.txt')));
// $snails = [
// 	'[[[0,[4,5]],[0,0]],[[[4,5],[2,6]],[9,5]]]',
// 	'[7,[[[3,7],[4,3]],[[6,3],[8,8]]]]',
// 	'[[2,[[0,8],[3,4]]],[[[6,7],1],[7,[1,6]]]]',
// 	'[[[[2,4],7],[6,[0,5]]],[[[6,8],[2,8]],[[2,1],[4,5]]]]',
// 	'[7,[5,[[3,8],[1,4]]]]',
// 	'[[2,[2,2]],[8,[8,1]]]',
// 	'[2,9]',
// 	'[1,[[[9,3],9],[[9,0],[0,7]]]]',
// 	'[[[5,[7,4]],7],1]',
// 	'[[[[4,2],2],6],[8,7]]',
// ];

$maxMagnitude = -INF;

class SnailNumber
{
	const TYPE_PAIR = 0;
	const TYPE_NUMBER = 1;

	public $left;
	public $right;

	public $level;

	public $parent;

	public $type;

	public function __construct(int $level, int $type, ?SnailNumber $parent = null)
	{
		$this->level = $level;
		$this->parent = $parent;
		$this->type = $type;
	}

	public function increaseLevel()
	{
		global $explosions;

		$this->level++;

		if ($this->type === self::TYPE_NUMBER)
		{
			return;
		}

		if ($this->level === 4)
		{
			$explosions[] = $this;
		}

		if ($this->left !== null && $this->right !== null)
		{
			$this->left->increaseLevel();
			$this->right->increaseLevel();
		}
	}

	public function explode()
	{
		global $numbers, $splits;

		// Left
		$this->explodeSide(true);

		// eval(\Psy\sh());

		// Right
		$this->explodeSide(false);

		if ($this->left->left >= 10)
		{
			$splits--;
		}

		if ($this->right->left >= 10)
		{
			$splits--;
		}

		foreach ($numbers as $k => $v)
		{
			if ($v === $this->left)
			{
				$index = $k;
				break;
			}
		}

		unset($numbers[$index]);
		unset($numbers[$index]);
		$numbers->add($index, $this);

		// Turn self into a number of 0
		$this->type = self::TYPE_NUMBER;
		$this->right = null;
		$this->left = 0;
		$this->level--;
	}

	public function explodeSide(bool $isLeft)
	{
		$sideCheck1 = $isLeft ? 'left' : 'right';
		$sideCheck2 = $isLeft ? 'right' : 'left';

		$current = $this;
		$parent = $this->parent;

		do
		{
			$isCheck = $current === $parent->$sideCheck1;
			$current = $parent;
			$parent = $parent->parent;
		}
		while ($isCheck === true && $parent !== null);

		if ($isCheck === false)
		{
			$node = $current->$sideCheck1;
			while ($node->type !== self::TYPE_NUMBER)
			{
				$node = $node->$sideCheck2;
			}

			if ($node->type === self::TYPE_NUMBER)
			{
				$node->increaseNumber($this->$sideCheck1->left);
			}
		}
	}

	public function increaseNumber(int $number)
	{
		global $splits;

		$alreadyASplit = $this->left >= 10;

		$this->left += $number;

		if ($this->left >= 10 && ! $alreadyASplit)
		{
			$splits++;
		}
	}

	public function split()
	{
		global $explosions, $numbers, $splits;

		$number = $this->left;
		$this->level++;

		foreach ($numbers as $k => $v)
		{
			if ($v === $this)
			{
				$index = $k;
				break;
			}
		}

		unset($numbers[$index]);

		$this->left = new self($this->level, self::TYPE_NUMBER, $this);
		$this->right = new self($this->level, self::TYPE_NUMBER, $this);

		$numbers->add($index, $this->right);
		$numbers->add($index, $this->left);

		$this->left->parent = $this;
		$this->right->parent = $this;

		if ($this->level === 4)
		{
			$explosions[] = $this;
		}

		$splits--;

		$this->left->left = (int)floor($number / 2);
		$this->right->left = (int)ceil($number / 2);
		$this->type = self::TYPE_PAIR;

		if ($this->left->left >= 10)
		{
			$splits++;
		}

		if ($this->right->left >= 10)
		{
			$splits++;
		}
	}
}

for ($i = 0; $i < count($snails) - 1; $i++)
{
	for ($j = $i + 1; $j < count($snails); $j++)
	{
		performAddition($snails[$i], $snails[$j]);
		performAddition($snails[$j], $snails[$i]);
	}
}

echo $maxMagnitude, "\n";

function magnitude(SnailNumber $sn)
{
	if ($sn->type === SnailNumber::TYPE_PAIR)
	{
		$left = magnitude($sn->left);
		$right = magnitude($sn->right);

		return $left * 3 + $right * 2;
	}
	else
	{
		return $sn->left;
	}
}

function performAddition(string $lSnails, string $rSnails) : void
{
	global $explosions, $splits, $numbers, $maxMagnitude;

	$explosions = [];
	$splits = 0;
	$numbers = new SplDoublyLinkedList;

	$lChain = makeChain($lSnails);
	$rChain = makeChain($rSnails);

	$lChain->increaseLevel();
	$rChain->increaseLevel();

	$main = new SnailNumber(0, SnailNumber::TYPE_PAIR);
	$main->left = $lChain;
	$main->right = $rChain;
	$lChain->parent = $main;
	$rChain->parent = $main;

	while (count($explosions) + $splits)
	{
		$exp = count($explosions);

		if (count($explosions))
		{
			$explosionNode = array_shift($explosions);
			$explosionNode->explode();
			continue;
		}

		if ($splits)
		{
			foreach ($numbers as $number)
			{
				if ($number->left >= 10)
				{
					$number->split();

					continue 2;
				}
			}
		}
	}

	$magnitude = magnitude($main);

	if ($magnitude > $maxMagnitude)
	{
		$maxMagnitude = $magnitude;
	}
}

function makeChain(string $input, ?SnailNumber $parent = null, int $level = 0)
{
	global $numbers;

	if ($input[0] === '[')
	{
		$input = substr($input, 1, -1);
		// Locate comma
		$currLevel = $level;
		$i = 0;
		do
		{
			switch ($input[$i])
			{
				case '[':
					$currLevel++;
				break;
				case ']':
					$currLevel--;
				break;
				case ',':
					if ($currLevel === $level)
					{
						break 2;
					}
				break;
			}

			$i++;
		}
		while ($i < strlen($input));

		$sn1 = substr($input, 0, $i);
		$sn2 = substr($input, $i + 1);

		$sn = new SnailNumber($level++, SnailNumber::TYPE_PAIR);
		$sn->left = makeChain($sn1, $sn, $level);
		$sn->right = makeChain($sn2, $sn, $level);
		$sn->parent = $parent;

		return $sn;
	}
	else
	{
		$sn = new SnailNumber($level - 1, SnailNumber::TYPE_NUMBER);
		$sn->left = (int)$input;
		$sn->parent = $parent;

		$numbers->push($sn);

		return $sn;
	}
}

function drawChain(SnailNumber $sn, ?SnailNumber $parent = null, int $level = 0)
{
	if ($sn->type === SnailNumber::TYPE_PAIR)
	{
		if ($parent !== $sn->parent)
		{
			echo 'shite, parent pair';
			exit;
		}
		if ($level === 4)
		{
			echo "\033[01;31m";
		}
		echo '[';
		echo drawChain($sn->left, $sn, $level + 1);
		echo ',';
		echo drawChain($sn->right, $sn, $level + 1);
		echo ']';
		if ($level === 4)
		{
			echo "\033[0m";
		}
	}
	else
	{
		if ($parent !== $sn->parent)
		{
			echo 'lol';
			exit;
		}
		if ($level - 1  === 4)
		{
		}
		echo $sn->left;
	}

	if ($parent === null)
	{
		echo "\n";
	}
}
