<?php

class Node
{
	public $cost;
	public $parent;
	public $moviment;
	public $depth;
	public $grid;


	public function __construct($cost = '', $parent = '', $moviment = '', $depth = '', $grid = '')
	{
		$this->cost = $cost;
		$this->parent = $parent;
		$this->moviment = $moviment;
		$this->depth = $depth;
		$this->grid = $grid;
	}

}

?>
