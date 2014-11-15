<?php namespace App\Http\Controllers\Modules\BlogModule;

use App\Http\Controllers\PanelController;
use View;

class Admin extends PanelController {
		
	public function get_index()
	{
		return 'Administracja tej kategorii';
	}

}