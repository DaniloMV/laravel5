<?php namespace App\Http\Controllers\Applications;

use App\Http\Controllers\PanelController;
use View;
use DB;
use Foundation;
use Input;

class BlockController extends PanelController {
	
	private $section;

	public function additionalMenu()
	{
		$html = '<ul class="pricing-table"> <li class="title">Menu aplikacji</li>';
		$html .= '<li class="bullet-item"><a style="width:100%; margin-bottom:5px;"href="/admin/blocks/home" class="button tiny">
					HomePage Block
				  </a></li>
				  <li class="bullet-item"><a style="width:100%; margin-bottom:5px;"href="/admin/blocks/default" class="button tiny">
					Defaults Block
				  </a></li>';
		
		$html .= '</ul>';
		

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

		if(empty($this->section) || $this->section == 'home') {
			$this->section = 'home';
			$where = 'is_homepage';
		} else {
			$this->section = 'default';
			$where = 'is_default';
		}

		$ff = new Foundation;
		//$form['url'] = 'admin/categories/'.$this->action;
		$form['url'] = 'admin/blocks/'.$this->section;
		
		$ff->startForm($form);
                
		//wszystkie bloczki
                $blocklist = array();
                $list = DB::select("select * from core_block where lang = 'pl'");
                foreach((array)$list as $blocks) {
                     $blocklist[$blocks->id] = array('id' => $blocks->id, 'name' => $blocks->nazwa);
                }
                
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
                        if(isset($blocklist[$element->block_id]))
                                $placed[$element->block_id] = array(
                                        'region' => $element->region,
                                        'position' => $element->position
                                );
		}

		$position = 0;
		foreach($blocklist as $block) {
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
			$this->section = 'default';
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
        
        public function get_edit()
        {
            $this->id = $this->getRouter()->current()->getParameter('id');
            list($lang, $id) = explode('_', $this->id);
            $data = DB::table('core_block')->where('lang', $lang)->where('id', $id)->first();
            $edit = json_decode($data->config);
            
            $ff = new Foundation;
            $form['url'] = 'admin/blocks/' . $this->action . '/' . $this->id;
            $ff->startForm($form);
            $ff->addHidden('id',$data->id);
            $ff->addHidden('lang',$data->lang);
            $ff->addText('nazwa','LABEL',$data->nazwa);
            require app_path('Http/Controllers/Blocks/TestBlock/BlockEdit.php');
            $ff->closeForm();

            return $ff->show();

        }
        
        public function post_edit()
        {
            
            $input = Input::all();
            $data = $input;
            unset($data['id']);
            unset($data['lang']);
            unset($data['nazwa']);
            unset($data['_token']);
            
            DB::table('core_block')
                ->where('id', $input['id'])
                ->where('lang', $input['lang'])
                ->update(array('nazwa' => $input['nazwa'], 'config' => json_encode($data)));
            
            return $this->get_edit();
        }
}