<?php

$hexCodes = rtrim(file_get_contents('d16.txt'));

$parsedHexCodes = '';
for ($i = 0; $i < strlen($hexCodes); $i++)
{
	$parsedHexCodes .= str_pad(base_convert($hexCodes[$i], 16, 2), 4, '0', STR_PAD_LEFT);
}

$version = 0;
function calculateVersion(int $i)
{
	global $parsedHexCodes, $version;

	$version += bindec($parsedHexCodes[$i++] . $parsedHexCodes[$i++] . $parsedHexCodes[$i++]);

	$packetType = bindec($parsedHexCodes[$i++] . $parsedHexCodes[$i++] . $parsedHexCodes[$i++]);

	if ($packetType === 4)
	{
		$literal = '';
		do
		{
			$char = $parsedHexCodes[$i++];
			$literal .= $parsedHexCodes[$i++] . $parsedHexCodes[$i++] . $parsedHexCodes[$i++] . $parsedHexCodes[$i++];
		}
		while ($char === '1');

		return [$i, base_convert($literal, 2, 10)];
	}

	$lengthType = $parsedHexCodes[$i++];

	if ($lengthType === '0')
	{
		$subpacketsLength = bindec(
			$parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
		);

		$end = $i + $subpacketsLength;
		$packets = [];
		do
		{
			[$i, $packets[]] = calculateVersion($i);
		}
		while ($i !== $end);

		$result = calculateOperation($packetType, $packets);

		return [$i, $result];
	}
	else
	{
		$packetsLength = bindec(
			$parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
			. $parsedHexCodes[$i++]
		);

		$packets = [];
		while ($packetsLength--)
		{
			[$i, $packets[]] = calculateVersion($i);
		}

		$result = calculateOperation($packetType, $packets);

		return [$i, $result];
	}
}

function calculateOperation(int $packetType, array $packets) : int
{
	switch ($packetType)
	{
		case 0:
			return array_sum($packets);
		break;
		case 1:
			return array_product($packets);
		break;
		case 2:
			return min($packets);
		break;
		case 3:
			return max($packets);
		break;
		case 5:
			return $packets[0] > $packets[1];
		break;
		case 6:
			return $packets[0] < $packets[1];
		break;
		case 7:
			return $packets[0] === $packets[1];
		break;
	}
}

[, $result] = calculateVersion(0);

echo $result, "\n";
