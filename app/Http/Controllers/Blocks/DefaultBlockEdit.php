<?php namespace App\Http\Controllers\Blocks;

abstract class DefaultBlockEdit {
        
	/* DefaultBlock::init()
	 * Initiallize configuration for block
	 * including its region specific
	 */
	public function init(Foundation &$ff)
	{   
            $this->form($ff);
	}
        
        public function form($ff) 
        {
            //body
        }
}