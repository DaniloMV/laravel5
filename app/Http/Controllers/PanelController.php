<?php namespace App\Http\Controllers;

use View;

abstract class PanelController extends Controller {

	private $action = null;

	public function __construct()
	{
		//wymagaj logowania
		$this->middleware('auth');
	}

	public function init()
	{
		
		$this->action = $this->getRouter()->current()->getParameter('action');
		$this->method = $this->getRouter()->getCurrentRequest()->getMethod();
		if(empty($this->action)) {
			$this->action = 'index';
		}

		$call_action = strtolower($this->method).'_'.$this->action;

		$data['content'] = $this->{$call_action}();

		return View::make('admin/master', $data);
	}

	public function get_index()
	{
		echo 'extends_panel_controller';	
	}
}
