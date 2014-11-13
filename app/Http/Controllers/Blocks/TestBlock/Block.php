<?php namespace App\Http\Controllers\Blocks\TestBlock;

use App\Http\Controllers\Blocks\DefaultBlock;
use View;

class Block extends DefaultBlock {

	public function index()
	{
		$this->data['content'] = 'test';

		return View::make('blocks/TestBlock/block', $this->data);
	}

}
