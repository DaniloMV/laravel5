<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use HTML;

abstract class Controller extends BaseController {

	use ValidatesRequests;

        private $script = array();
        
        public function pushLabJS($name, $wait = false)
        {
            $this->script[$name] = $wait;
        }
        
        public function renderLabsJS()
        {
            $html = HTML::script('/packages/labjs/LAB.min.js');
            if(!empty($this->script)) {
                $html .= "\t<script>\n\t\t ".'$LAB'."";

                foreach($this->script as $name => $value) {

                    $html .= "\n\t\t\t";
                    $html .= '.script("'.$name.'")';
                    if($value)
                        $html .= '.wait()';
                    
                }
                $html .= "\n\t</script>";
            }
            
            return $html;
        }
}
