<?php namespace App\Http\Controllers\Blocks;

use View;

abstract class DefaultBlock {


	private $config = array (
		'container' => 'containers/block',
	);
        
        private $controller;

	/* DefaultBlock::init()
	 * Initiallize configuration for block
	 * including its region specific
	 */
	public function init($region, &$controller)
	{
		$cfg = array();
		$this->class = get_called_class();
                $this->controller = $controller;

		$config = str_replace(array('\\', 'App/'),array('/',''),$this->class . 'Config.inc.php');
		include app_path($config);

		$this->config = array_merge($this->config, $cfg);

		// wczytywanie dla regionu
		if(!empty($this->config[$region]))
			$this->config = array_merge($this->config, $this->config[$region]);
	}


	/* DefaultBlock::render()
	 * Render body
	 */
	public function render()
	{
		$this->data['content'] = $this->index();
		return View::make($this->config['container'], $this->data);
	}

        public function pushLabJS($name, $wait = false)
        {
            $this->controller->pushLabJS($name, $wait);
        }
        
	/* DefaultBlock::index()
	 * Just for specification
	 */
	public function index()
	{
		$html = 'Is not initiallized yet!';
		return $html;
	}

	/* DefaultBlock::partial()
	 * Just for specification
	 */
	public function partial($name = null, $data = array())
	{
		if(!empty($name))
			return View::make('partials/'.$name, $data);
		else
			return '';
	}
}