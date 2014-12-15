<?php namespace App\Http\Controllers;

use DB;

class AjaxController extends Controller {

	public function ajax()
        {
            list($lang,$idcat) = explode('_',$_GET['element_id']);
            $menublock = $_GET['menu_id'];
            
            if($_GET['parent_id'] == 'x_0') {
                DB::update('update core_categories set menu_block = ?, id_rodzica = NULL where lang = ? AND id = ?', array($menublock, $lang, $idcat));
            }
            else {
                list($parlang,$parcat) = explode('_',$_GET['parent_id']);
                DB::update('update core_categories set menu_block = ?, id_rodzica = ? where lang = ? AND id = ?', array($menublock, $parcat, $lang, $idcat));
            }
        }
}
