<?php 
	/**
	* Autores
	* Melissa
	* Allan
	* Leonardo Clemente
	*/

	/**
	* Link GitHub: https://github.com/meseren/inteligenciaartificial/edit/master/seguinte.php
	*/

	error_reporting(0);
	
	require_once 'Node.class.php';

	$objetivo = [1, 2, 3, 4, 5, 6, 7, 8, ''];

	/*--------------------------------------*
	| Movimentos possíveis no quebra-cabeça |
	*---------------------------------------*/
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
	/*---------------*
	| Fim movimentos |
	*----------------*/

	/*--------------------------------------------*
     | Identifica o index do ponto vazio no array |
     |											  |
     | @param array $no                           |
     |                                            |
     | @return int                                |
	 * -------------------------------------------*/
	function retornaIndexVazio($no)
	{
		foreach ($no->matriz as $key => $value)
			if(empty($value))
				return $key;
	}

	/*------------------------------------------------------------------------------------------*
     | Identifica em que posição da "matriz" está o ponto vazio, de acordo com o index do array |
     |																							|
     | @param int $index Posição do vetor que está vázia										|
     |																							|
     | @return string																			|
	 *------------------------------------------------------------------------------------------*/
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

	/*--------------------------------------------------------*
     | Gera todos os nós sucessores a partir de um nó enviado |
     |														  |
     | @param object $raiz Nó que será expandido.			  |
     |														  |
     | @return array										  |
	*---------------------------------------------------------*/
	function retornaSucessor($raiz)
	{
		$lista = array();

		$index = retornaIndexVazio($raiz);

		$coordenadaVazia = retornaCoordenadasVazio($index);

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

	/*------------------------------------------*
     | Verifica se o nó é o meu Objetivo Final  |
     |							  			    |
     | @param object $raiz Nó que será validado.|
     | @param array $objetivo Objetico Final.   |
     | @return boolean						    |
	*-------------------------------------------*/
	function testeOjetivo($no, $objetivo)
	{
		foreach ($no as $i => $value)
			if($value != $objetivo[$i])
				return false;
			
		return true;
	}

	/*------------------*
     | Início da Buscas |
	*-------------------*/
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
			
			//print_r($list);
		}
	}
	/*---------------*
     | Fim da Buscas |
	*----------------*/
	
	
	/*------------------*
     | Setup para teste |
 	 *------------------*/
	$raiz = new Node(0, '', '', 0, [1, 2, 3, 4, 5, 6, 7, '', 8]);

	print '<pre>';
	print(buscaLargura($raiz, $objetivo));
	
