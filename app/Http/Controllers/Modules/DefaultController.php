<?php namespace App\Http\Controllers\Modules;

use Illuminate\Routing\Controller;
use App\Http\Controllers\Blocks as Blocks;
use View;
use DB;

abstract class DefaultController extends Controller {

	protected $regions = array( '1', '2', '3', '4', '5' );
	protected $layout = 'layout.master';
	protected $actions = array();
	protected $id = null;
	protected $lang = null;
	protected $homepage = false;

	public function __construct()
	{
		$route = $this->getRouter()->current();
		$this->actions = $route->getAction();
		
		if(isset($this->actions['id'])) {
			$this->id = $this->actions['id'];
		} else {
			$this->homepage = true;
		}

		$this->lang = $this->actions['lang'];

		if($this->homepage)
			$whereSql = " is_homepage = TRUE ";
		else {
			$individual = DB::table('core_block_position')
						->where(array('category_id' => $this->id, 'lang' => $this->lang))
						->count();

			if(intval($individual))
			{
				$whereSql = " category_id = ".$this->id;
			}
			else
			{
				$whereSql = " is_default = TRUE ";
			}
		}

		$list = DB::select("
			select 
				block_id, region
			from 
				core_block_position as c
			left join 
				core_block_position_row as r
			on 
				id = parent_id AND
				c.lang = r.lang
			where 
				". $whereSql ." AND 
				c.lang = '".$this->lang."'");

		/* BLOCK LOADING */
		foreach($this->regions as $id) {
			$reg = 'region'.$id;
			$this->data[$reg] = '';
		}

		foreach($list as $element) {
			$variable = 'TestBlock';
			$reg = 'region'.$element->region;
			$classname = '\\App\\Http\\Controllers\\Blocks\\'.$variable.'\\Block';
			$block = new $classname();
			$block->init($reg);
			if(!empty($this->data[$reg])) {
				$part['p1'] = $this->data[$reg];
				$part['p2'] = $block->render();
				$this->data[$reg] = $part['p1'] . $part['p2'];
			} else {
				$this->data[$reg] = $block->render();
			}
		}
	}

	public function partial($name = null, $data = array())
	{
		if(!empty($name))
			return View::make('partials/'.$name, $data);
		else
			return '';
	}

}
