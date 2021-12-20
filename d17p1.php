<?php

preg_match('%y=(-?\d+)%', file_get_contents('d17.txt'), $matches);
[, $minY] = $matches;

$initialYVel = abs($minY) - 1;
echo ($initialYVel ** 2 + $initialYVel) / 2, "\n";
