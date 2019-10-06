<?php

class Node
{
	public $custo;
	public $pai;
	public $movimento;
	public $profundidade;
	public $matriz;


	public function __construct($custo = '', $pai = '', $movimento = '', $profundidade = '', $matriz = '')
	{
		$this->custo = $custo;
		$this->pai = $pai;
		$this->movimento = $movimento;
		$this->profundidade = $profundidade;
		$this->matriz = $matriz;
	}

}

?>
