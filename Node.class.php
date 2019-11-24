<?php

class Node
{
	public $custo;
	public $pai;
	public $movimento;
	public $profundidade;
	public $matriz;
	public $h1;
	public $h2;
	public $f1;
	public $f2;

	public function __construct($custo = '', $pai = '', $movimento = '', $profundidade = '', $matriz = '', $h1 = '', $h2 = '', $f1 = '', $f2 = '')
	{
		$this->custo = $custo;
		$this->pai = $pai;
		$this->movimento = $movimento;
		$this->profundidade = $profundidade;
		$this->matriz = $matriz;
		$this->h1= $h1;
		$this->h2= $h2;
		$this->f1 = $f1;
		$this->f2 = $f2;
	}

}

?>
