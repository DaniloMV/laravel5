<?php namespace App\Http\Controllers;

use View;
use DB;

abstract class PanelController extends Controller {

	public $action = null;

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

		$this->call_action = strtolower($this->method).'_'.$this->action;

		$data['content'] = $this->getContent();
		$data['menumodulu'] = $this->additionalMenu();
		$data['menukategorii'] = $this->categoryMenu();

		return View::make('admin/master', $data);
	}

	public function getContent()
	{
		return $this->{$this->call_action}();
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
			$menu .= '<a href="/admin/'.$cat->lang.$cat->id.'">'.$cat->name.'</a><a href="/admin/categories/'.$cat->lang.'_'.$cat->id.'"><span style="float:right; display:inline-block; padding: 3px; background:red; margin-top:10px;"></span></a><br>';
		}

		return $menu;
	}
}
