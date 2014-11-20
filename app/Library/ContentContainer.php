<?php namespace App\Library;

use View;

class ContentContainer {

	private $html;
	public $type;
	public $header;
	public $class;

	public function __construct($type)
	{
		$this->html = '';
		$this->header = '';
		$this->class = '';
		$this->type = $type;
		$this->unique = rand();
	}


	public function addContent($content)
	{
		$this->html .= $content;
	}

	public function getContent()
	{
		return $this->html;
	}

	public function getTab($tabContent, $tab_class)
	{
		$tab ='<dl class="tabs" data-tab>';
		$active = (isset($_POST['tab_active'])) ? $_POST['tab_active'] : 0;
		$i = 0;

		foreach($tabContent as $tmp) {

				$isactive = ($i == $active) ? 'active' : '';
				
				if(!empty($tab_class[$i]) || !empty($isactive))
					$class = 'class="'.$tab_class[$i].' '.$isactive.'"';
				else
					$class = '';

				$tab .= '<dd '.$class.' onclick="$(this).parent().prev().val(\''.$i.'\')" ><a href="#panel'.++$i.'">'.$tmp.'</a></dd>';
				
		}
		$tab .= '</dl>';

		return $tab;
	}

	public function getTabContent($tabContent)
	{
		$class = 'class="content"';
		$html ='<div class="tabs-content">';
		$i = 0;
		$active = (isset($_POST['tab_active'])) ? $_POST['tab_active'] : 0;

		foreach($tabContent as $tmp) {
			$isactive = ($i == $active) ? 'active' : '';
			$class = 'class="content '.$isactive.'"';
		  	$html .= '<div '.$class.' id="panel'.++$i.'">'.$tmp.'</div>';
		}
		$html .= '</div>';

		return $html;
	}

}