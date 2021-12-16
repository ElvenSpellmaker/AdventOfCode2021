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

		return $i;
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
		do
		{
			$i = calculateVersion($i);
		}
		while ($i !== $end);

		return $i;
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

		while ($packetsLength--)
		{
			$i = calculateVersion($i);
		}

		return $i;
	}
}

calculateVersion(0);

echo $version, "\n";
