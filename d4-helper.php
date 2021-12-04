<?php

class BingoSquare
{
	private $winnerSum;

	private $columns = [];

	private $rows = [];

	private $columnCount = 0;

	private $rowCount = 0;

	public function __construct(&$winnerSum)
	{
		$this->winnerSum = &$winnerSum;
	}

	public function addValues(array $row) : void
	{
		foreach ($row as $number)
		{
			$this->rows[$this->rowCount][$number] = $number;
			$this->columns[$this->columnCount++][$number] = $number;
		}

		$this->rowCount++;
		$this->columnCount = 0;
	}

	public function markOffNumber(int $number) : void
	{
		$calculateWinnerSum = false;

		foreach ($this->columns as $colNum => $column)
		{
			foreach ($column as $_)
			{
				unset($this->columns[$colNum][$number]);
			}

			if (! count($this->columns[$colNum]))
			{
				$calculateWinnerSum = true;
				break;
			}
		}

		foreach ($this->rows as $rowNum => $row)
		{
			foreach ($row as $_)
			{
				unset($this->rows[$rowNum][$number]);
			}

			if (! count($this->rows[$rowNum]))
			{
				$calculateWinnerSum = true;
				break;
			}
		}

		if ($calculateWinnerSum === true)
		{
			$this->calculateWinnerSum();
		}
	}

	public function calculateWinnerSum() : void
	{
		$winnerSum = 0;
		$seenCount = [];
		foreach ($this->columns as $column)
		{
			foreach ($column as $number)
			{
				$winnerSum += $number;
				$seenCount[$number] = true;
			}
		}

		foreach ($this->rows as $row)
		{
			foreach ($row as $number)
			{
				if (! isset($seenCount[$number]))
				{
					$winnerSum += $number;
				}
			}
		}

		$this->winnerSum = $winnerSum;
	}
}
