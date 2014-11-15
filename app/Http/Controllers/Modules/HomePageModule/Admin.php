<?php namespace App\Http\Controllers\Modules\HomePageModule;

use App\Http\Controllers\PanelController;
use View;

class Admin extends PanelController {
		
	public function get_index()
	{
		return 'AdminHomePageX';
	}

}