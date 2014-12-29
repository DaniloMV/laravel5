<?php namespace App\Http\Controllers\Blocks\TestBlock;

use App\Http\Controllers\Blocks\DefaultBlock;
use View;
use DB;
use Input;
use Foundation;

class Admin extends DefaultBlock {
        
        public function edit(Foundation &$ff, $edit)
        {
            $dane = (!empty($edit->liczba_danych)) ? $edit->liczba_danych : '';
            $ff->addText('liczba_danych','Liczba danych', $dane);
        }
        
        public function save()
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
        }

}
