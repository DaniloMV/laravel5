<?php namespace App\Http\Controllers\Applications;

use App\Http\Controllers\PanelController;
use View;

class UserController extends PanelController {
	
	public function get_index()
	{
		echo 'AdminHomePage';
	}
}