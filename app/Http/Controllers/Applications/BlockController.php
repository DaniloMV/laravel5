<?php namespace App\Http\Controllers\Applications;

use App\Http\Controllers\PanelController;
use View;
use DB;

class BlockController extends PanelController {
	
	public function get_index()
	{
		//wszystkie bloczki
		$imitateDatabaseList = array(
			array('name' => 'Block1', 'id' => '1'),
			array('name' => 'Block2', 'id' => '2'),
			array('name' => 'Block3', 'id' => '3'),
			array('name' => 'Block4', 'id' => '4'),
			array('name' => 'Block5', 'id' => '5'),
		);

		//rozmieszczone bloczki
		$list = DB::select("
			select block_id, region, position
			from core_block_position as c
			left join core_block_position_row as r
			on id = parent_id AND
			c.lang = r.lang
			where is_homepage = TRUE AND c.lang = 'pl' 
			order by position asc
		");

		$placed = array();
		foreach($list as $element) {
			$placed[$element->block_id] = array('region' => $element->region, 'position' => $element->position);
		}

		$position = 0;
		foreach($imitateDatabaseList as $block) {
			if(isset($placed[$block['id']])) $region = $placed[$block['id']];
			else $region = array('region' => '0', 'position' => ++$position);

			$data['block'][$region['region']][$region['position']] = array('id' => $block['id'], 'name' => $block['name']);
			ksort($data['block'][$region['region']]);
		}

		return View::make('admin/blocks_controller', $data);
	}

	public function post_index()
	{
		$data['block'] = $_POST['block'];

		DB::delete('delete from core_block_position where is_homepage = TRUE');

		$id = DB::table('core_block_position')->insertGetId(
    		array('category_id' => 0, 'lang' => 'pl', 'is_homepage' => TRUE, 'is_default' => FALSE)
		);
		
		$blocks = array();
		unset($data['block']['0']);
		if(!empty($data['block'])) {
			foreach($data['block'] as $region => $block) {
				foreach($block as $block_id => $record) {
				 	$blocks[] = array('parent_id' => $id, 'lang' => 'pl', 
				 		'region' => $region, 'block_id' => $block_id, 'position' => $record);
				}
			}
			DB::table('core_block_position_row')->insert($blocks);
		}

		return $this->get_index();
	}
}