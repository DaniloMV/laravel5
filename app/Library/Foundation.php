<?php namespace App\Library;

use View;
use ContentContainer;
use Form;

class Foundation {

	private $containerID = 0;
	private $contentContainers = array();
	private $html = '';

	public function startForm($parameters) {
		$this->addContent(Form::open($parameters));
	}

	public function closeForm($submit = 'Save', $back = '') 
	{
		$content = '';
		$content .= Form::submit($submit, array('class' => 'button'));
		$content .= Form::close();
		$this->addContent($content);
	}	

	public function startTab($name, $class = '')
	{
		$this->containerID++;
		$this->contentContainers[$this->containerID] = new ContentContainer('tabs');
		$this->containerID++;
		$this->contentContainers[$this->containerID] = new ContentContainer('tab');
		$this->contentContainers[$this->containerID]->header = $name;
		$this->contentContainers[$this->containerID]->class = $class;
	}

	public function addTab($name, $class = '')
	{
		$this->containerID++;
		$this->contentContainers[$this->containerID] = new ContentContainer('tab');
		$this->contentContainers[$this->containerID]->header = $name;
		$this->contentContainers[$this->containerID]->class = $class;
	}

	public function closeTab()
	{
		$headers = array();
		$temp = array();
		$html = '';

		while($this->contentContainers[$this->containerID]->type != 'tabs' ) {
			$temp[] = $this->contentContainers[$this->containerID]->getContent();
			$classes[] = $this->contentContainers[$this->containerID]->class;
			$headers[] = $this->contentContainers[$this->containerID]->header;
			unset($this->contentContainers[$this->containerID]);
			$this->containerID--;
		}
		
		$temp = array_reverse($temp);
		$classes = array_reverse($classes);
		$headers = array_reverse($headers);
		
		$active = (isset($_POST['tab_active'])) ? $_POST['tab_active'] : 0;
		
		$html .= Form::hidden('tab_active', $active);
		$html .= $this->contentContainers[$this->containerID]->getTab($headers, $classes);
		$html .= $this->contentContainers[$this->containerID]->getTabContent($temp);

		unset($this->contentContainers[$this->containerID]);
		$this->containerID--;
		$this->addContent($html);
	}

	public function addContent($content)
	{
		if(!empty($this->contentContainers)) {
			$this->contentContainers[$this->containerID]->addContent($content);		
		} else {
			$this->html .= $content;
		}
	}

	public function show()
	{
		return $this->html;
	}

}