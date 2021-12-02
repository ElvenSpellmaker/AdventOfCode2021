<?php

$file = file_get_contents('d2.txt');
$pos = 0;
$depth = 0;
$aim = 0;

function forward($x) { global $pos, $depth, $aim; $pos += $x; $depth += $aim * $x; }
function up($x) { global $aim; $aim -= $x; }
function down($x) { global $aim; $aim += $x; }

eval(preg_replace('% (\d+)%', '($1);', $file));

echo $pos * $depth, "\n";
