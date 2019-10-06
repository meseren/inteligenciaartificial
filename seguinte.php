<?php 

	require_once 'Node.class.php';

	error_reporting(0);

	$list = array();

	$root = [1, 2, 3, 4, 5, 6, 7, '', 8];

	$objective = [1, 2, 3, 4, 5, 6, 7, 8, ''];

	function up($root, $element, $coordinates)
	{
		if(substr($coordinates,1,1) != '3')
		{
			print $element;
			$root[$element] = $root[$element + 3];
			$root[$element + 3] = '';

			return $root;
		}

		return false;
	}

	function down($root, $element, $coordinates)
	{
		if(substr($coordinates,1,1) != '1')
		{
			$root[$element] = $root[$element - 3];
			$root[$element - 3] = '';

			return $root;
		}

		return false;
	}

	function left($root, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '3')
		{
			$root[$element] = $root[$element + 1];
			$root[$element + 1] = '';

			return $root;
		}

		return false;
	}

	function right($root, $element, $coordinates)
	{
		if(substr($coordinates,3,-1) != '1')
		{
			$root[$element] = $root[$element - 1];
			$root[$element - 1] = '';

			return $root;
		}

		return false;
	}

	function returnPositionFree($root)
	{
		foreach ($root->matriz as $key => $value)
			if(empty($value))
				return $key;
	}

	function returnCoordinates($index)
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

	function nextCondition($root)
	{
		$list = array();

		#Descobrindo qual linha a posição livre está
		$index = returnPositionFree($root);

		$position = returnCoordinates($index);

		$left = left($root->matriz, $index, $position);
		$down = down($root->matriz, $index, $position);
		$up = up($root->matriz, $index, $position);
		$right = right($root->matriz, $index, $position);

		if($down !== false){
			$node = new Node();
			$node->matriz = $down;
			$node->pai = $root;
			$node->movimento = 'down';
			$node->profundidade = $root->profundidade + 1;
			$node->custo = $root->custo + 1;
			
			array_push($list, $node);
		}

		if($up !== false){
			$node = new Node();
			$node->matriz = $up;
			$node->pai = $root;
			$node->movimento = 'up';
			$node->profundidade = $root->profundidade + 1;
			$node->custo = $root->custo + 1;
			array_push($list, $node);
		}

		if($right !== false){
			$node = new Node();
			$node->matriz = $right;
			$node->pai = $root;
			$node->movimento = 'right';
			$node->profundidade = $root->profundidade + 1;
			$node->custo = $root->custo + 1;
			
			array_push($list, $node);
		}

		if($left !== false){
			$node = new Node();
			$node->matriz = $left;
			$node->pai = $root;
			$node->movimento = 'left';
			$node->profundidade = $root->profundidade + 1;
			$node->custo = $root->custo + 1;
			
			array_push($list, $node);
		}

		return $list;
	}

	function objectiveTest($root, $objective)
	{
		foreach ($root as $i => $value)
			if($value != $objective[$i])
				return false;
			
		return true;
	}

	function widthSearch($root, $objective)
	{
		$list = array();
		array_push($list, $root);

		while (count($list) > 0) {

			$node = $list[0];
			array_shift($list); 

			if(objectiveTest($node->matriz, $objective))
				return $node;
			else
				$list =  nextCondition($node);

			//print_r($list);
		}		
	}
	
	function depthSearch($root, $objective)
	{
		$list = array();
		array_push($list, $root);

		while (count($list) > 0) {
			$node = $list[count($list)-1];
			
			array_pop($list); 

			if(objectiveTest($node->matriz, $objective))
				return $node;
			else	
				$list = nextCondition($node);

			//print_r($list);
		}	
	}
	
	function depthLimitSearch($root, $objective, $limite)
	{
		$list = array();
		array_push($list, $root);

		while (count($list) > 0) {
			$node = $list[count($list)-1];

			array_pop($list); 

			if(objectiveTest($node->matriz, $objective)){
				return $node;
			}
			else
				if($node->profundidade < $limite)
					$list =  nextCondition($node);

			//print_r($list);
		}

		return null;	
	}
	
	function iterativeDeepeningSearch($root, $objective)
	{
		$limite = 0;
		
		do
		{
		   ++$limite;
		   $r = depthLimitSearch($root, $objective, $limite);
		}while(is_null($r));
		
		print 'Limite >>> '.$limite.'<br><br>';

		return $r;
	}

	function orderCost($list)
	{
		for ($i=0; $i < count($list); $i++){
			for ($j=1; $j < count($list); $j++){
				if($list[$i]->custo > $list[$j]->custo)
				{
					$aux = $list[$i];
					$list[$i] = $list[$j];
					$list[$j] = $aux;
				}
			}
		}

		return $list;
	}

	function custoUniforme($root, $objective)
	{
		$list = array();

		array_push($list, $root);

		while (count($list) > 0) {
			$list = orderCost($list);
			$node = $list[0];

			array_shift($list); 

			if(objectiveTest($node, $objective))
				return $list;
			else	
				$list = nextCondition($node);
			
			print_r($list);
		}
	}
	
	
	$root = new Node(0, '', '', 0, [1, 2, 3, 4, 5, 6, 7, '', 8]);

	print '<pre>';
	print_r(custoUniforme($root, $objective));
	