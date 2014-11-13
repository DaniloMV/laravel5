<?php namespace App\Http\Controllers\Modules\BlogModule;

use App\Http\Controllers\Modules\DefaultController;
use View;

class Module extends DefaultController {
		
	public function indexCMS()
	{
		return View::make('hello', $this->data);
	}

}