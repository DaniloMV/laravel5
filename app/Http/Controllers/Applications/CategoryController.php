<?php namespace App\Http\Controllers\Applications;

use App\Http\Controllers\PanelController;
use View;
use DB;
use Foundation;

class CategoryController extends PanelController {
	
	private $lang;
	private $id;

	public function getContent() {
		
		$html = '';
		list($jezyk,$kategoria) = explode('_',$this->action);
		$this->lang = $jezyk;
		$this->id = $kategoria;

		#pobieranie bloków
		$funkcja = strtolower($this->method).'_blockSetting';
		$html .= $this->{$funkcja}();

		$ff = new Foundation;
		$form['url'] = 'admin/categories/'.$this->action;
		
		$ff->startForm($form);
		$ff->startTab('podstawowe dane','block-icon');
		$ff->addContent('sss');

		$ff->addTab('zarzadzanie blokami');
		$ff->addContent($html);

		$ff->addTab('narzedzia seo');
		$ff->addContent('sss');

		$ff->closeTab();
		$ff->closeForm();

		
		return $ff->show();
	}

	public function get_blockSetting()
	{
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
				category_id = '".$this->id."' AND 
				c.lang = '".$this->lang."' 
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
		$data['action'] = 'admin/categories/'.$this->action;

		return View::make('admin/blocks_controller', $data);
	}

	public function post_blockSetting()
	{
		$params = array('category_id' => $this->id, 'lang' => $this->lang, 'is_homepage' => FALSE, 'is_default' => FALSE);

		$data['block'] = $_POST['block'];

		DB::delete('delete from core_block_position where category_id = '.$this->id);

		$id = DB::table('core_block_position')->insertGetId(
			$params
		);
		
		$blocks = array();
		unset($data['block']['0']);
		if(!empty($data['block'])) {
			foreach($data['block'] as $region => $block) {
				foreach($block as $block_id => $record) {
				 	$blocks[] = array('parent_id' => $id, 'lang' => $this->lang, 
				 		'region' => $region, 'block_id' => $block_id, 'position' => $record);
				}
			}
			DB::table('core_block_position_row')->insert($blocks);
		}

		return $this->get_blockSetting();
	}
}