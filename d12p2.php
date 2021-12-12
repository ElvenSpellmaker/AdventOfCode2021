<?php

ini_set('memory_limit', '200M');

function path_split(string $path)
{
	return explode('-', $path);
}

$nodePaths = array_map('path_split', explode("\n", rtrim(file_get_contents('d12.txt'))));

/**
 * @var Node[] $nodesMap
 */
$nodesMap = [];

class Node
{
	private $paths = [];

	public function insert(string $nodeName)
	{
		$this->paths[] = $nodeName;
	}

	public function remove(string $nodeName) : void
	{
		foreach ($this->paths as $pathKey => $pathName)
		{
			if ($nodeName === $pathName)
			{
				unset($this->paths[$pathKey]);
				break;
			}
		}
	}

	public function getPaths() : array
	{
		return $this->paths;
	}
}

foreach ($nodePaths as [$nodePathStart, $nodePathEnd])
{
	$nodesMap[$nodePathStart] = $nodesMap[$nodePathStart] ?? new Node;
	$nodesMap[$nodePathEnd] = $nodesMap[$nodePathEnd] ?? new Node;

	$nodesMap[$nodePathStart]->insert($nodePathEnd);
	$nodesMap[$nodePathEnd]->insert($nodePathStart);
}

foreach ($nodesMap as $node)
{
	$node->remove('start');
}

function traverseNodes(string $current, array $pathChain, ?string $lowerUsed = null)
{
	global $nodesMap;

	$pathChain[] = $current;

	// echo join(',', $pathChain), "\n";

	// Hit the end of the chain
	if ($current === 'end')
	{
		return [$pathChain];
	}

	// Hit a dead end, it might still be possible to recover if
	// the parent is a capital or hasn't been visited twice and
	// no other lower caves have been visited.
	if (count($nodesMap[$current]->getPaths()) === 0)
	{
		return [$pathChain];
	}

	$newPaths = [];

	$explorePaths = $nodesMap[$current]->getPaths();
	foreach ($explorePaths as $path)
	{
		$localLowerUsed = $lowerUsed;
		if (strtolower($path) === $path && in_array($path, $pathChain))
		{
			if ($localLowerUsed !== null)
			{
				// echo "not going to: $path: ";
				// echo join(',', $pathChain), "\n";
				continue;
			}

			$localLowerUsed = $path;
		}

		$paths = traverseNodes($path, $pathChain, $localLowerUsed);

		foreach($paths as $newPath)
		{
			if (end($newPath) === 'end')
			{
				$newPaths[] = $newPath;
				continue;
			}
		}
	}

	return $newPaths;
}


echo count(traverseNodes('start', [])), "\n";

// foreach (traverseNodes('start', []) as $node)
// {
// 	echo join(',', $node), "\n";
// }
