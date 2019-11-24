<?php 

    set_time_limit(0);

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

	$GLOBALS['objetivo'] = [1, 2, 3, 4, 5, 6, 7, 8, ''];
	$GLOBALS['totalNos'] = 0;
	$GLOBALS['profundidadeEncontrada'] = 0;

	/*-------------------------------------*
     | Retorna o valor de B*               |
     |						               |
     | @param int $totalNos, $profundidade |
     |                                     |
     | @return int                         |
	 * ------------------------------------*/
	function bEstrela($totalNos, $profundidade)
	{
		$max = 5;
		$min = 0;

		$error = 0.01;

		$n = 0;
		$b = 0;

		while(abs($n - $totalNos) > $error)
		{
			$b = ($min + $max)/2;
			$n = n($b, $profundidade);

			if($n > $totalNos)
				$max = $b;
			else
				$min = $b;
		}

		return $b;
	}

	function n($b, $d)
	{
		$n = 0;

		for ($i=0; $i < $d+1; $i++)
			$n += pow($b, $i);
		
		return $n;
	}

	/*-----------------------------------*
	| Posições do quebra-cabeça original |
	*------------------------------------*/
	function posicaoCorreta($item)
	{
		switch ($item) {
			case '1':
				return '[1,1]';

			case '2': 
				return '[1,2]';

			case '3':
				return '[1,3]';

			case '4':
				return '[2,1]';

			case '5':
				return '[2,2]';

			case '6':
				return '[2,3]';

			case '7':
				return '[3,1]';

			case '8':
				return '[3,2]';

			case '':
				return '[3,3]';
		}
	}

	/*--------------------------------------*
	| Movimentos possíveis no quebra-cabeça |
	*---------------------------------------*/
	function cima($no, $vazio, $coordenadas)
	{
		if(substr($coordenadas,1,1) != '3')
		{
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

	/*---------------------------------------------------------*
     | Retorna a quantidade de peças que estçao fora do lugar |
     |											              |
     | @param array $no, $objetivo                            |
     |                                                        |
     | @return int                                            |
	 * --------------------------------------------------------*/
	function h1($no, $objetivo)
	{
		$aux = 0;

		foreach ($no->matriz as $key => $value) 
			if($objetivo[$key] != $value && !empty($value))
				$aux++;
		
		return $aux;
	}

	/*---------------------------------------------------------------------------------------------------*
     | Retorna a somatória da quantidade de peças que estão fora do lugar [1,1] != [2,1] : (2-1) + (1-1) |
     |											                                                         |
     | @param array $no, $objetivo                                                                       |
     |                                                                                                   |
     | @return int                                                                                       |
	 * -------------------------------------------------------------------------------------------------*/
	function h2($no, $objetivo)
	{
		$aux =0;

		foreach ($no->matriz as $key => $value)
		{
			if(!empty($value)){

				$cordenadaAtual = retornaCoordenadas($key);
				$cordenadaCorreta = posicaoCorreta($value);

				if($cordenadaCorreta != $cordenadaAtual){

					$x = (substr($cordenadaAtual,1 , 1) - substr($cordenadaCorreta,1 , 1)); 
					#O que importa é somente o módulo, por isso a multiplicação
					if($x < 0)
						$x *= -1;

					$y = (substr($cordenadaAtual,3 , 1) - substr($cordenadaCorreta,3 , 1)); 
					#O que importa é somente o módulo, por isso a multiplicação
					if($y < 0)
						$y *= -1;

					$aux += ($x + $y); 
				}
			}

		}

		return $aux;
	}

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
	function retornaCoordenadas($index)
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
	function retornaSucessor($raiz, $lista)
	{
		$index = retornaIndexVazio($raiz);

		$coordenadaVazia = retornaCoordenadas($index);

		$esquerda = esquerda($raiz->matriz, $index, $coordenadaVazia);
		$baixo = baixo($raiz->matriz, $index, $coordenadaVazia);
		$cima = cima($raiz->matriz, $index, $coordenadaVazia);
		$direita = direita($raiz->matriz, $index, $coordenadaVazia);

		if($baixo !== false){
			$node = new Node();
			$node->matriz = $baixo;
			$node->pai = $raiz;
			$node->movimento = 'baixo';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;

			#Cálculo da Heurística 1
			$node->h1 = h1($node, $GLOBALS['objetivo']);
			
			#Cálculo da Heurística 2
			$node->h2 = h2($node, $GLOBALS['objetivo']);

			#Cálculo de f(n) = G(n) + H1(n)
			$node->f1 = $node->custo + $node->h1;

			#Cálculo de f(n) = G(n) + H2(n)
			$node->f2 = $node->custo + $node->h2;

			array_push($lista, $node);
			$GLOBALS['totalNos']++;
		}

		if($cima !== false){
			$node = new Node();
			$node->matriz = $cima;
			$node->pai = $raiz;
			$node->movimento = 'cima';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;

			#Cálculo da Heurística 1
			$node->h1 = h1($node, $GLOBALS['objetivo']);

			#Cálculo da Heurística 2
			$node->h2 = h2($node, $GLOBALS['objetivo']);

			#Cálculo de f(n) = G(n) + H1(n)
			$node->f1 = $node->custo + $node->h1;

			#Cálculo de f(n) = G(n) + H2(n)
			$node->f2 = $node->custo + $node->h2;

			array_push($lista, $node);
			$GLOBALS['totalNos']++;
		}

		if($direita !== false){
			$node = new Node();
			$node->matriz = $direita;
			$node->pai = $raiz;
			$node->movimento = 'direita';
			$node->profundidade = $raiz->profundidade + 1;	
			$node->custo = $raiz->custo + 1;

			#Cálculo da Heurística 1
			$node->h1 = h1($node, $GLOBALS['objetivo']);

			#Cálculo da Heurística 2
			$node->h2 = h2($node, $GLOBALS['objetivo']);

			#Cálculo de f(n) = G(n) + H1(n)
			$node->f1 = $node->custo + $node->h1;

			#Cálculo de f(n) = G(n) + H2(n)
			$node->f2 = $node->custo + $node->h2;
			
			array_push($lista, $node);
			$GLOBALS['totalNos']++;
		}

		if($esquerda !== false){
			$node = new Node();
			$node->matriz = $esquerda;
			$node->pai = $raiz;
			$node->movimento = 'esquerda';
			$node->profundidade = $raiz->profundidade + 1;
			$node->custo = $raiz->custo + 1;

			#Cálculo da Heurística 1
			$node->h1 = h1($node, $GLOBALS['objetivo']);

			#Cálculo da Heurística 2
			$node->h2 = h2($node, $GLOBALS['objetivo']);

			#Cálculo de f(n) = G(n) + H1(n)
			$node->f1 = $node->custo + $node->h1;

			#Cálculo de f(n) = G(n) + H2(n)
			$node->f2 = $node->custo + $node->h2;
			
			array_push($lista, $node);
			$GLOBALS['totalNos']++;
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
	function testeObjetivo($no, $objetivo)
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

			if(testeObjetivo($node->matriz, $objetivo))
				return $node;
			else
				$lista =  retornaSucessor($node, $lista);

			//print_r($lista);
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

			if(testeObjetivo($node->matriz, $objetivo))
				return $node;
			else	
				$lista = retornaSucessor($node, $lista);

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

			if(testeObjetivo($node->matriz, $objetivo)){
				return $node;
			}
			else
				if($node->profundidade < $limite)
					$lista =  retornaSucessor($node, $lista);

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
		$GLOBALS['profundidadeEncontrada'] = $limite;

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

	function ordenarH2($lista)
	{
		for ($i=0; $i < count($lista); $i++){
			for ($j=1; $j < count($lista); $j++){
				if($lista[$i]->h2 > $lista[$j]->h2)
				{
					$aux = $lista[$i];
					$lista[$i] = $lista[$j];
					$lista[$j] = $aux;
				}
			}
		}

		return $lista;
	}

	function ordenarH1($lista)
	{
		for ($i=0; $i < count($lista); $i++){
			for ($j=1; $j < count($lista); $j++){
				if($lista[$i]->h1 > $lista[$j]->h1)
				{
					$aux = $lista[$i];
					$lista[$i] = $lista[$j];
					$lista[$j] = $aux;
				}
			}
		}

		return $lista;
	}

	function ordenarF1($lista)
	{
		for ($i=0; $i < count($lista); $i++){
			for ($j=1; $j < count($lista); $j++){
				if($lista[$i]->f1 > $lista[$j]->f1)
				{
					$aux = $lista[$i];
					$lista[$i] = $lista[$j];
					$lista[$j] = $aux;
				}
			}
		}

		return $lista;
	}

	function ordenarF2($lista)
	{
		for ($i=0; $i < count($lista); $i++){
			for ($j=1; $j < count($lista); $j++){
				if($lista[$i]->f2 > $lista[$j]->f2)
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
		$lista = array();

		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$lista = ordenarCusto($lista);
			$node = $lista[0]; 		 	

			#Remove o primeiro da lista
			array_shift($lista); 

			if(testeObjetivo($node, $objetivo))
				return $node;
			else	
				$lista = retornaSucessor($node, $lista);
			
			//print_r($lista);

		}
	}

	function buscaGulosah1($raiz, $objetivo)
	{
		$lista = array();

		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$lista = ordenarH1($lista);
			$node = $lista[0];

			#Remove o primeiro da lista, pois este será o com menor h1
			array_shift($lista); 

			if($node->h1 == 0){
				$GLOBALS['profundidadeEncontrada'] = $node->profundidade;
				return $node;
			}
			else	
				$lista = retornaSucessor($node, $lista);

			//print_r($lista);
		}
	}

	function buscaGulosah2($raiz, $objetivo)
	{
		$lista = array();

		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$lista = ordenarH2($lista);
			$node = $lista[0];

			#Remove o primeiro da lista, pois este será o com menor h2
			array_shift($lista); 

			if($node->h1 == 0){
				$GLOBALS['profundidadeEncontrada'] = $node->profundidade;
				return $node;
			}
			else	
				$lista = retornaSucessor($node, $lista);

			//print_r($lista);
		}
	}

	function buscaAEstrelah1($raiz, $objetivo)
	{
		$lista = array();

		array_push($lista, $raiz);

		while (count($lista) > 0) {
			$lista = ordenarF1($lista);
			$node = $lista[0];

			#Remove o primeiro da lista, pois este será o com menor f(n) = g(n) + h1(n)
			array_shift($lista); 

			if($node->h1 == 0){
				$GLOBALS['profundidadeEncontrada'] = $node->profundidade;
				return $node;
			}
			else	
				$lista = retornaSucessor($node, $lista);

			//print_r($lista);
		}
	}

	function buscaAEstrelah2($raiz, $objetivo)
	{
		$lista = array();

		array_push($lista, $raiz);

		while (count($lista) > 0) {

			$lista = ordenarF2($lista);
			$node = $lista[0];

			#Remove o primeiro da lista, pois este será o com menor f(n) = g(n) + h2(n)
			array_shift($lista); 

			if($node->h1 == 0){
				$GLOBALS['profundidadeEncontrada'] = $node->profundidade;
				return $node;
			}
			else	
				$lista = retornaSucessor($node, $lista);

			//print_r($lista);
		}
	}
	/*---------------*
     | Fim das Buscas |
	*----------------*/
	
	/*------------------*
     | Setup para teste |
 	 *------------------*/

	# Define a lista de estados profundidade 2
    $d2['1'] = array(1, 2, '', 4, 5, 3, 7, 8, 6);
    $d2['2'] = array(1, 2, 3, 4, '', 6, 7, 5, 8);
    $d2['3'] = array(1, 2, 3, 4, 5, 6, '', 7, 8);
    $d2['4'] = array(1, 2, 3, 4, '', 6, 7, 5, 8);

    # Define a lista de estados de profundidade 4
    $d4['1'] = array('', 1, 2, 4, 5, 3, 7, 8, 6);
    $d4['2'] = array(1, 2, '', 4, 6, 3, 7, 5, 8);
    $d4['3'] = array(1, 2, 3, 5, '', 6, 4, 7, 8);
    $d4['4'] = array(1, 3, '', 4, 2, 6, 7, 5, 8);

    # Define a lista de estados de profundidade 6
    $d6['1'] = array(4, 1, 2, 5,'', 3, 7, 8, 6);
    $d6['2'] = array('', 1, 2, 4, 6, 3, 7, 5, 8);
    $d6['3'] = array(1, 2, 3, 5, 6, 8, 4, 7, '');
    $d6['4'] = array(1, 3, 6, 4, 2, 8, 7, 5, '');

    # Define a lista de estados de profundidade 8
    $d8['1'] = array(4, 1, 2, 5, 3, 6, 7, 8, '');
    $d8['2'] = array(4, 1, 2, 6, '', 3, 7, 5, 8);
    $d8['3'] = array(1, 2, 3, 5, 6, 8, '', 4, 7);
    $d8['4'] = array(1, 3, 6, 4, '', 8, 7, 2, 5);

    # Define a lista de estados de profundidade 10
    $d10['1'] = array(4, 1, 2, 5, 3, 6, '', 7, 8);
    $d10['2'] = array(4, 1, 2, 6, 5, 3, '', 7, 8);
    $d10['3'] = array('', 2, 3, 1, 6, 8, 5, 4, 7);
    $d10['4'] = array('', 1, 6, 4, 3, 8, 7, 2, 5);

    # Define a lista de estados de profundidade 12
    $d12['1'] = array(4, 1, 2, 3, '', 6, 5, 7, 8);
    $d12['2'] = array('', 1, 2, 4, 5, 3, 6, 7, 8);
    $d12['3'] = array(2, 6, 3, 1, '', 8, 5, 4, 7);
    $d12['4'] = array(4, 1, 6, 3, '', 8, 7, 2, 5);

    # Define a lista de estados de profundidade 14
    $d14['1'] = array('', 4, 2, 3, 1, 6, 5, 7, 8);
    $d14['2'] = array(1, 5, 2, 4, '', 3, 6, 7, 8);
    $d14['3'] = array(2, 6, 3, 5, 1, 8, '', 4, 7);
    $d14['4'] = array(4, 1, '', 3, 8, 6, 7, 2, 5);

    # Define a lista de estados de profundidade 16
    $d16['1'] = array(3, 4, 2, 1, '', 6, 5, 7, 8);
    $d16['2'] = array(1, 5, 2, 4, 7, 3, '', 6, 8);
    $d16['3'] = array(2, 6, 3, 5, '', 8, 4, 1, 7);
    $d16['4'] = array(4, 8, 1, 3, '', 6, 7, 2, 5);

    # Define a lista de estados de profundidade 18
    $d18['1'] = array(3, 4, 2, 1, 7, 6, '', 5, 8);
    $d18['2'] = array(1, 5, 2, 7, '', 3, 4, 6, 8);
    $d18['3'] = array('', 6, 3, 2, 5, 8, 4, 1, 7);
    $d18['4'] = array(4, 8, 1, 7, 3, 6, '', 2, 5); # A*: 4811 nós gerados 

    # Define a lista de estados de profundidade 20
    $d20['1'] = array('', 4, 2, 3, 7, 6, 1, 5, 8);
    $d20['2'] = array(1, 5, '', 7, 3, 2, 4, 6, 8);
    $d20['3'] = array(6, 3, '', 2, 5, 8, 4, 1, 7);
    $d20['4'] = array(4, 8, 1, 7, 3, 6, 2, 5, '');


    $raiz = new Node(0, '', '', 0, $d2[1]);
    $raiz->h1 =  $raiz->f1 = h1($raiz, $GLOBALS['objetivo']);
    $raiz->h2 = $raiz->f2 = h2($raiz, $GLOBALS['objetivo']);

    print '<pre> <h3>Nó Final: </h3>';

	/*---*
     | OA|
 	 *---*/
	#print_r(buscaAprofundamentoIterativo($raiz, $GLOBALS['objetivo']));
	#print_r(buscaProfundidade($raiz, $GLOBALS['objetivo']));
	#print_r(buscaLargura($raiz, $GLOBALS['objetivo']));
	#print_r(buscaProfundidadeLimite($raiz, $GLOBALS['objetivo'], 2));
	#print_r(custoUniforme($raiz, $GLOBALS['objetivo']));

    /*---*
     | PO|
 	 *---*/
	#print_r(buscaGulosah1($raiz, $GLOBALS['objetivo']));
	#print_r(buscaGulosah2($raiz, $GLOBALS['objetivo']));
	#print_r(buscaAEstrelah1($raiz, $GLOBALS['objetivo']));
	#print_r(buscaAEstrelah2($raiz, $GLOBALS['objetivo']));
  
	for ($i=1; $i <= count($d10) ; $i++) { 
    	$raiz = new Node(0, '', '', 0, $d10[$i]);
    	$raiz->h1 =  $raiz->f1 = h1($raiz, $GLOBALS['objetivo']);
    	$raiz->h2 = $raiz->f2 = h2($raiz, $GLOBALS['objetivo']);

		buscaGulosah2($raiz, $GLOBALS['objetivo']);

		print 'Total Nós IDS:'.$GLOBALS['totalNos']."\n";
		print 'Profundidade: '.$GLOBALS['profundidadeEncontrada']."\n";
		print 'b*:'.bEstrela($GLOBALS['totalNos'], $GLOBALS['profundidadeEncontrada'])."\n";  

		$GLOBALS['profundidadeEncontrada'] = 0;
		$GLOBALS['totalNos'] = 0;
    }


	