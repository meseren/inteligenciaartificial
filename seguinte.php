<?php 

	error_reporting(0);
	
	require_once 'Node.class.php';

	$objetivo = [1, 2, 3, 4, 5, 6, 7, 8, ''];

	function calculaForaLugar($estado, $objetivo)
	{
		$cont = 0;

		foreach ($estado->matriz as $key => $value)
			if($objetivo[$key] != $value)
				$cont++;
		
		return $cont;
	}

	function verificaAdjacencia($no, $vazio, $lista)
	{
		if(($no-3) == $vazio)
			return true;
		else 
			if(($no+3) == $vazio)
				return true;
			else 
				if(($no-1) == $vazio)
					return true;
				else 
					if(($no+1) == $vazio)
						return true;

		return false;
	}

	function cima($no, $vazio, $coordenadas)
	{
		if(substr($coordenadas,1,1) != '3')
		{
			print $vazio;
			$no[$vazio] = $no[$vazio + 3];
			$no[$vazio + 3] = '';

			return $no;
		}

		return false;
	}

	function baixo($no, $vazio, $coordenadas)
	{
		if(substr($coordenadas,1,1) != '1')
		{
			$no[$vazio] = $no[$vazio - 3];
			$no[$vazio - 3] = '';

			return $no;
		}

		return false;
	}

	function esquerda($no, $vazio, $coordenadas)
	{
		if(substr($coordenadas,3,-1) != '3')
		{
			$no[$vazio] = $no[$vazio + 1];
			$no[$vazio + 1] = '';

			return $no;
		}

		return false;
	}

	function direita($no, $vazio, $coordenadas)
	{
		if(substr($coordenadas,3,-1) != '1')
		{
			$no[$vazio] = $no[$vazio - 1];
			$no[$vazio - 1] = '';

			return $no;
		}

		return false;
	}

	function retornaIndexVazio($no)
	{
		foreach ($no->matriz as $key => $value)
			if(empty($value))
				return $key;
	}

	function retornaCoordenadasVazio($index)
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

	function retornaSucessor($raiz)
	{
		$lista = array();

		#Descobrindo qual linha a posição livre está
		$index = retornaIndexVazio($raiz);

		$coordenadaVazia = retornaCoordenadasVazio($index);

		#Faz todos os movimentos
		$baixo = baixo($raiz->matriz, $index, $coordenadaVazia);
		$esquerda = esquerda($raiz->matriz, $index, $coordenadaVazia);
		$cima = cima($raiz->matriz, $index, $coordenadaVazia);
		$direita = direita($raiz->matriz, $index, $coordenadaVazia);

		if($baixo !== false){
			$node = new Node();
			$node->matriz = $baixo;
			$node->pai = $raiz;
			$node->movimento = 'baixo';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;
			
			array_push($lista, $node);
		}

		if($cima !== false){
			$node = new Node();
			$node->matriz = $cima;
			$node->pai = $raiz;
			$node->movimento = 'cima';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;
			array_push($lista, $node);
		}

		if($direita !== false){
			$node = new Node();
			$node->matriz = $direita;
			$node->pai = $raiz;
			$node->movimento = 'direita';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;
			
			array_push($lista, $node);
		}

		if($esquerda !== false){
			$node = new Node();
			$node->matriz = $esquerda;
			$node->pai = $raiz;
			$node->movimento = 'esquerda';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;
			
			array_push($lista, $node);
		}

		return $lista;
	}

	function testeOjetivo($no, $objetivo)
	{
		foreach ($no as $i => $value)
			if($value != $objetivo[$i])
				return false;
			
		return true;
	}

	function buscaLargura($raiz, $objetivo)
	{
		$lista = array();
		array_push($lista, $raiz);

		while (count($lista) > 0) {

			$node = $lista[0];

			#Remove o primeiro da lista
			array_shift($lista); 

			if(testeOjetivo($node->matriz, $objetivo))
				return $node;
			else
				$lista =  retornaSucessor($node);

			print_r($lista);
		}		
	}
	
	function buscaProfundidade($raiz, $objetivo)
	{
		$lista = array();
		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$node = $lista[count($lista)-1];
			
			#Remove o último da lista
			array_pop($lista); 

			if(testeOjetivo($node->matriz, $objetivo))
				return $node;
			else	
				$lista = retornaSucessor($node);

			//print_r($lista);
		}	
	}
	
	function buscaProfundidadeLimite($raiz, $objetivo, $limite)
	{
		$lista = array();
		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$node = $lista[count($lista)-1];

			#Remove o último da lista
			array_pop($lista); 

			if(testeOjetivo($node->matriz, $objetivo)){
				return $node;
			}
			else
				if($node->profundidade < $limite)
					$lista =  retornaSucessor($node);

			//print_r($lista);
		}

		return null;	
	}
	
	function buscaAprofundamentoIterativo($raiz, $objetivo)
	{
		$limite = 0;
		
		do
		{
		   ++$limite;
		   $r = buscaProfundidadeLimite($raiz, $objetivo, $limite);
		}while(is_null($r));
		
		print 'Limite >>> '.$limite.'<br><br>';

		return $r;
	}

	function ordenarCusto($lista)
	{
		for ($i=0; $i < count($lista); $i++){
			for ($j=1; $j < count($lista); $j++){
				if($lista[$i]->custo > $lista[$j]->custo)
				{
					$aux = $lista[$i];
					$lista[$i] = $lista[$j];
					$lista[$j] = $aux;
				}
			}
		}

		return $lista;
	}

	function custoUniforme($raiz, $objetivo)
	{
		$list = array();

		array_push($list, $raiz);

		while (count($list) > 0) {
			$list = ordenarCusto($list);
			$node = $list[0];

			#Remove o primeiro da lista
			array_shift($list); 

			if(testeOjetivo($node, $objetivo))
				return $list;
			else	
				$list = retornaSucessor($node);
			
			print_r($list);
		}
	}
	
	
	$raiz = new Node(0, '', '', 0, [1, 2, 3, 4, 5, 6, 7, 8, '']);

	$vazio = retornaIndexVazio($raiz);

	if(verificaAdjacencia(8, $vazio))
		print 'eh adjacente';
	else 
		print 'nao eh adjacente';
	
	exit;

	print $vazio;
	exit;
	print '<pre>';
	print(calculaForaLugar($raiz, $objetivo));
	