<?php namespace App\Http\Controllers\Blocks\TestBlock2;

use App\Http\Controllers\Blocks\DefaultBlock;

class Block extends DefaultBlock {

	public function __construct()
	{
		var_dump('test_block2');
	}

	public function indexCMS()
	{
		return View::make('hello', $this->data);
	}

}
