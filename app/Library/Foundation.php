<?php namespace App\Library;

use View;

class Foundation {

	private $tabID = 0;
	private $tabContent = array();
	private $tempTabContent = array();
	private $html = array();

	public function __construct()
	{
		
	}
	
	public function startTab()
	{
		$this->tabID++;
		$this->tempTabContent[$this->tabID]['current'] = 0;
	}

	public function addTab($name)
	{
		$this->tempTabContent[$this->tabID]['current']++;	
		$this->tempTabContent[$this->tabID]['tab'][$this->tempTabContent[$this->tabID]['current']] = $name;
	}

	public function closeTab()
	{
		$this->tabContent[] = $this->tempTabContent[$this->tabID];
		unset($this->tempTabContent[$this->tabID]);

		$this->tabID--;

		if(!$this->tabID) {
			$this->html[] = $this->renderTabs();
		}
	}

	public function renderTabs()
	{

		$class = 'class="active"';
		$tab ='<dl class="tabs" data-tab>';

		foreach($this->tabContent as $tmp) {
			foreach($tmp['tab'] as $id => $tabc)
			{
				$tab .= '<dd '.$class.'><a href="#panel'.$id.'">'.$tabc.'</a></dd>';
				$class = '';
			}
		}
		$tab .= '</dl>';

		$class = 'class="content active"';
		$html ='<div class="tabs-content">';
		foreach($this->tabContent as $tmp) {
			foreach($tmp['html'] as $id => $content)
			{
				foreach($content as $code)
				{
				  $html .= '<div '.$class.' id="panel'.$id.'">'.$code.'</div>';
				  $class = 'class="content"';
				}
			}
		}
		$html .= '</div>';

		return $tab.$html;		

	}

	public function addContent($content)
	{
		if($this->tabID) {
			$this->tempTabContent[$this->tabID]['html'][$this->tempTabContent[$this->tabID]['current']][] = $content;
		} else {
			$this->html[] = $content;
		}
	}

	public function renderContent()
	{
		$html = '';
		foreach($this->html as $content)
		{
			$html .= $content;
		}

		return $html;
	}

}