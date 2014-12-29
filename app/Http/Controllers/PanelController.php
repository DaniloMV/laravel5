<?php namespace App\Http\Controllers;

use View;
use DB;

abstract class PanelController extends Controller {

	public $action = null;

	public function __construct()
	{
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

                $kategorie = $this->categoryMenu();
		$data['content'] = $this->getContent();
		$data['menumodulu'] = $this->additionalMenu();
		$data['menukategorii'] = $kategorie['container'];
		$data['struktura'] = $kategorie['tree'];
                
                $this->pushLabJS('/packages/foundation/js/foundation/foundation.tab.js',true);
                
                $data['LABscript'] = $this->renderLabsJS();

		return View::make('admin/master', $data);
	}

	public function getContent()
	{
                return $this->callAction($this->call_action,array());
	}

	public function additionalMenu()
	{
		return '';
	}

	public function categoryMenu()
	{
		$menu = array();
                $tree = array();

		$list = DB::select("select * from core_block where lang = 'pl' and is_menu = TRUE");
                foreach((array)$list as $menus) {
                     $tree[] = array('id' => $menus->id, 'text' => $menus->nazwa, 'parent' => '#', 'state' => array('disabled' => 'true', 'opened' => 'true'), 'data' => array('icon' => "<div style='height:24px'>i</div>"));
                }
                
                $list = DB::select('select * from core_categories where id <> 0');
                foreach((array)$list as $cat) {
                    if(!empty($cat->id_rodzica)) {
                        $parent = $cat->lang.'_'.$cat->id_rodzica;
                    } else {
                        $parent = $cat->menu_block;
                    }
                    $tree[] = array('id' => $cat->lang.'_'.$cat->id, 'text' => $cat->name, 'parent' => $parent, 'a_attr' => array('href' => '/admin/'.$cat->lang.$cat->id), 'data' => array('icon' => "<div style='height:24px'>i</div>"));
                }
                
		$menu['container'] = '<ul class="pricing-table"> <li class="title">Drzewko kategorii</li><li style="background:white;"> <div id="jstree_demo_div"></div></li></ul>';
                $menu['tree'] = json_encode($tree);
                
		return $menu;
	}
}
