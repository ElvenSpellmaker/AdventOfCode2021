<?php

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
	private $pathName = '';

	private $paths = [];

	public function insert(string $nodeName)
	{
		$this->paths[] = $nodeName;
	}

	public function isLowerLeafPath() : ?string
	{
		return count($this->paths) === 1 && strtolower($this->paths[0]) === $this->paths[0]
			? $this->paths[0]
			: null;
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

foreach ($nodesMap as $nodeName => $node)
{
	$removePath = $node->isLowerLeafPath();
	if ($removePath !== null)
	{
		$nodesMap[$removePath]->remove($nodeName);
		unset($nodesMap[$nodeName]);
	}
}

function traverseNodes(string $current, array $pathChain)
{
	global $nodesMap;

	$pathChain[] = $current;

	// Hit the end of the chain
	if ($current === 'end')
	{
		return [$pathChain];
	}

	$newPaths = [];

	$explorePaths = $nodesMap[$current]->getPaths();
	foreach ($explorePaths as $path)
	{
		if (strtolower($path) === $path && in_array($path, $pathChain))
		{
			continue;
		}

		$paths = traverseNodes($path, $pathChain);

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
