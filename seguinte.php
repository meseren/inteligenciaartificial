<?php 
	
	$base = [1, 2, 3, '', 5, 6, 7, 8, 4];

	$objective = [1, 2, 3, 5, '', 6, 7, 8, 4];

	function up($base, $element, $coordinates)
	{
		
		if(substr($coordinates,1,1) != '1')
		{
			$base[$element] = $base[$element + 3];
			$base[$element + 3] = '';

			return $base;
		}

		return false;
	}

	function down($base, $element, $coordinates)
	{
		if(substr($coordinates,1,1) != '3')
		{
			$base[$element] = $base[$element - 3];
			$base[$element - 3] = '';

			return $base;
		}

		return false;
	}

	function left($base, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '1')
		{
			$base[$element] = $base[$element - 1];
			$base[$element - 1] = '';

			return $base;
		}

		return false;
	}

	function right($base, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '3')
		{
			$base[$element] = $base[$element + 1];
			$base[$element + 1] = '';

			return $base;
		}

		return false;
	}

	function returnPositionFree($base)
	{
		foreach ($base as $key => $value)
			if(empty($value))
				return $key;
	}

	function returnCoordinates($index, $base)
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

	function nextCondition($base)
	{
		#Descobrindo qual linha a posição livre está
		$element = returnPositionFree($base);

		$position = returnCoordinates($element, $base);

		$node = array();

		$down = down($base, $element, $position);
		$up = up($base, $element, $position);
		$left = left($base, $element, $position);
		$right = right($base, $element, $position);

		if($down !== false)
			$node[] = $down;

		if($up !== false)
			$node[] = $up;

		if($right !== false)
			$node[] = $right;

		if($left !== false)
			$node[] = $left;
		
		return $node;
	}

	function objectiveTest($base, $objective)
	{
		foreach ($base as $i => $value)
			if($base[$i] != $objective[$i])
				return false;
			
		return true;
	}

	$node = nextCondition($base);

	foreach ($node as $key => $value) {
		if(objectiveTest($value, $objective))
		{
			print "Finish!";
			break;
		}
	}

	print '<pre>';
	print_r($node);
	print_r($objective);

?>