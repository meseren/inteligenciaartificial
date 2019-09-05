<?php 
	error_reporting(0);

	$list = array();

	$root = [1, 5, 2, 7, 4, 3, 8, 6, ''];

	$objective = [1, 2, 3, 4, 5, 6, 7, 8, ''];

	function up($root, $element, $coordinates)
	{
		
		if(substr($coordinates,1,1) != '1')
		{
			$root[$element] = $root[$element + 3];
			$root[$element + 3] = '';

			return $root;
		}

		return false;
	}

	function down($root, $element, $coordinates)
	{
		if(substr($coordinates,1,1) != '3')
		{
			$root[$element] = $root[$element - 3];
			$root[$element - 3] = '';

			return $root;
		}

		return false;
	}

	function left($root, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '1')
		{
			$root[$element] = $root[$element - 1];
			$root[$element - 1] = '';

			return $root;
		}

		return false;
	}

	function right($root, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '3')
		{
			$root[$element] = $root[$element + 1];
			$root[$element + 1] = '';

			return $root;
		}

		return false;
	}

	function returnPositionFree($root)
	{
		foreach ($root as $key => $value)
			if(empty($value))
				return $key;
	}

	function returnCoordinates($index, $root)
	{
		if($index < 3)
			if($index < 2)
				if($index < 1)
					return '[1,1]';
				else
					return '[1,2]';
			else 
				return '[1,3]';
		else
			if($index < 6)
				if($index < 5)
					if($index < 4)
						return '[2,1]';
					else
						return '[2,2]';
				else
					return '[2,3]';
			else
				if($index < 8)
					if($index < 7)
						return '[3,1]';
					else
						return '[3,2]';
				else
					return '[3,3]';
	}

	function nextCondition($root, $list)
	{
		#Descobrindo qual linha a posição livre está
		$element = returnPositionFree($root);

		$position = returnCoordinates($element, $root);

		$down = down($root, $element, $position);
		$up = up($root, $element, $position);
		$left = left($root, $element, $position);
		$right = right($root, $element, $position);

		if($down !== false)
			array_push($list, $down);

		if($up !== false)
			array_push($list, $up);

		if($right !== false)
			array_push($list, $right);

		if($left !== false)
			array_push($list, $left);

		return $list;
	}

	function objectiveTest($root, $objective)
	{
		foreach ($root as $i => $value)
			if($root[$i] != $objective[$i])
				return false;
			
		return true;
	}

	function searchWidth($root,$list, $objective)
	{
		array_push($list, $root);
		while (count($list) > 0) {
			$node = $list[0];
			
			#Gambiarra
			unset($list[0]);
			$list = array_values($list); 

			if(objectiveTest($node, $objective))
				return $node;
			else	
				$list = nextCondition($node, $list);
		}		
	}

	print '<pre>';
	print_r(searchWidth($root, $list, $objective));
	exit;
?>