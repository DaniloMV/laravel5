<?php namespace App\Http\Controllers\Applications;

use App\Http\Controllers\PanelController;
use View;
use DB;
use Foundation;

class BlockController extends PanelController {
	
	private $section;

	public function additionalMenu()
	{
		$html = '<a style="width:100%; margin-bottom:5px;"href="/admin/blocks/home" class="button tiny">
					HomePage Block
				  </a>
				  <a style="width:100%; margin-bottom:5px;"href="/admin/blocks/default" class="button tiny">
					Defaults Block
				  </a>';
		
	 	return $html;
	}

	public function get_home()
	{
		$this->section = 'home';
		return $this->get_index();
	}

	public function get_default()
	{
		$this->section = 'default';
		return $this->get_index();
	}

	public function get_index()
	{
		$ff = new Foundation;
		//$form['url'] = 'admin/categories/'.$this->action;
		$form['url'] = 'admin/blocks/'.$this->section;
		
		$ff->startForm($form);

		if(empty($this->section) || $this->section == 'home') {
			$this->section = 'home';
			$where = 'is_homepage';
		} else {
			$where = 'is_default';
		}

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
			SELECT 
				block_id, region, position
			FROM 
				core_block_position AS c
			LEFT JOIN 
				core_block_position_row AS r
			ON 
				id = parent_id AND
				c.lang = r.lang
			WHERE 
				".$where." = TRUE AND 
				c.lang = 'pl' 
			ORDER BY position ASC
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

		$html = View::make('admin/blocks_controller', $data);
		
		$ff->addContent($html);
		$ff->closeForm();

		
		return $ff->show();
	}

	public function post_home()
	{
		if(empty($this->section) || $this->section == 'home') {
			$this->section = 'home';
			$where = 'is_homepage';
			$params = array('category_id' => 0, 'lang' => 'pl', 'is_homepage' => TRUE, 'is_default' => FALSE);
		} else {
			$where = 'is_default';
			$params = array('category_id' => 0, 'lang' => 'pl', 'is_homepage' => FALSE, 'is_default' => TRUE);
		}

		$data['block'] = $_POST['block'];

		DB::delete('delete from core_block_position where '.$where.' = TRUE');

		$id = DB::table('core_block_position')->insertGetId(
			$params
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

		$akcja = 'get_'.$this->section;

		return $this->$akcja();
	}

	public function post_default()
	{
		$this->section = 'default';
		return $this->post_home();
	}


}