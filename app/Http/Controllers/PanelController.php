<?php namespace App\Http\Controllers;

use View;
use DB;

abstract class PanelController extends Controller {

	private $action = null;

	public function __construct()
	{
		# wymagaj logowania
		$this->middleware('auth');
	}

	public function init()
	{
		
		$this->action = $this->getRouter()->current()->getParameter('action');
		$this->method = $this->getRouter()->getCurrentRequest()->getMethod();
		$route = $this->getRouter()->current();
		$this->route_parameter = $route->getAction();
		if(isset($this->route_parameter['id'])) {
			
		}

		if(empty($this->action)) {
			$this->action = 'index';
		}

		$call_action = strtolower($this->method).'_'.$this->action;

		$data['content'] = $this->{$call_action}();
		$data['menumodulu'] = $this->additionalMenu();
		$data['menukategorii'] = $this->categoryMenu();

		return View::make('admin/master', $data);
	}

	public function additionalMenu()
	{
		return '';
	}

	public function categoryMenu()
	{
		$menu = '<br>';
		$list = DB::select('select * from core_categories where id <> 0 ');
		foreach((array) $list as $cat) {
			$menu .= '<a href="/admin/'.$cat->lang.$cat->id.'">'.$cat->name.'</a><br>';
		}

		return $menu;
	}
}
