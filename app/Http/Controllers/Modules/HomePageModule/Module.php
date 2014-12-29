<?php namespace App\Http\Controllers\Modules\HomePageModule;

use App\Http\Controllers\Modules\DefaultController;
use View;

class Module extends DefaultController {

	public function index()
	{
                $this->data['LABscript'] = $this->renderLabsJS();
		return View::make('hello', $this->data);
	}

}
