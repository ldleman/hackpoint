<?php
class Fritzing{
	public $file; 
	public $files = array();
	public $xml,$parts = array();
	private $coreIndicator = '/fritzing-parts/';
	private $minY,$minX;
	public $ino ;
	public $debug ;
	function __construct($file){
		$this->file = $file;


		$zip = new ZipArchive;
		$res = $zip->open($this->file);
		$properties = array();

		if ($res !== TRUE)  throw new Exception('Impossible d\'ouvrir le ZIP, code:' . $res);


		for( $i = 0; $i < $zip->numFiles; $i++ ){ 
		    $stat = $zip->statIndex($i); 

	
			if(substr($stat['name'], -3) == '.fz') $this->xml = new SimpleXMLElement($zip->getFromName($stat['name']));
			if(substr($stat['name'], -4) == '.ino') $this->ino = $zip->getFromName($stat['name']);
			$this->files[$stat['name']] =  $zip->getFromName($stat['name']);
			
		}
		$zip->close();
		$this->parse();
		
	}

	private function parse(){
		

	
		$this->minY = 0;
		$this->minX = 0;
		foreach($this->xml->instances->instance as $instance){
			$part = array();
			$part['sigle'] = (string) $instance->title;

			$attributes = $instance->attributes();
			$part['id'] = (string) $attributes->moduleIdRef;
			$part['properties'] = array();
			foreach($instance->property as $property){
				$attribute = $property->attributes();
				$part['properties'][(string)$attribute['name']] = (string)$attribute['value'];
			}
			$part['type'] = 'component';

			if(isset($instance->views->breadboardView->geometry)){
				$geometry = $instance->views->breadboardView->geometry->attributes();

				$part['geometry'] = array(
						'x' => array(),
						'z' => 0,
						'y' => array()
				);


				if(isset($instance->views->breadboardView->geometry->transform)){
					$part['geometry']['r'] = $instance->views->breadboardView->geometry->transform;
					$this->debug = json_encode($instance->views->breadboardView->geometry->transform->attributes());
				}

				if(isset($geometry['wireFlags']))
					$part['type'] = 'wire';

				foreach ($geometry as $key => $value) {
					if(substr($key, 0,1) == 'x') $part['geometry']['x'][] =(float)$value;
					if(substr($key, 0,1) == 'y') $part['geometry']['y'][] =(float)$value;
				}
				
			
				$part['geometry']['z'] =(float)$geometry['z'];

				if($this->minY>$part['geometry']['y'][0])$this->minY = $part['geometry']['y'][0];
				if($this->minX>$part['geometry']['x'][0])$this->minX = $part['geometry']['x'][0];
				
				if(	$part['type']== 'wire'){
					$wireAttributes = $instance->views->breadboardView->wireExtras->attributes();
					$part['color'] = (string)$wireAttributes['color'];
					
	
				
				}
			}
			
			$part['component'] = $this->getComponent($attributes->path);
			

			$this->parts[] = $part;
		}
		$this->minY = abs($this->minY);
		$this->minX = abs($this->minX);
		
		
	}

	public function toHtml(){

		


		$html = '<svg id="vis" width="1500" height="500" ></svg>

		
		<script>
			 var vis = d3.select("#vis");';

		


				foreach ($this->parts as $part) { 
					if(!isset($part['component']) || $part['type']!='component' || !isset($part['component']['breadboard'])) continue;
				

				$html .= "var part = vis.append('svg');";
				$html .= "part.attr('x',".($part['geometry']['x'][0]+$this->minX).");";
				$html .= "part.attr('y',".($part['geometry']['y'][0]+$this->minY).");";
				

				$html .= "part.html('".str_replace(array("'","\n"),array("\'"," "),$part['component']['breadboard'])."');";

				//part.select('g').attr("transform","rotate(45)");
				 } 

				  foreach ($this->parts as $part) { 
			if( $part['type']!='wire' ) continue;

				$html .= 'var line = vis.append("polyline")'."\n\t\t".'.attr("stroke-linejoin","round").style("stroke", \''.$part['color'].'\').attr("stroke-width", 5)'."\n\t\t";
					$x = 0;
					$y = 0;
				$html .= '.attr("points","';
					$points = array();
					for ($i=0;$i<count($part['geometry']['x']);$i++) {
						$x += $part['geometry']['x'][$i];
						$y += $part['geometry']['y'][$i];
						if($i==0) $y+= $this->minY;
						if($i==0) $x+= $this->minX;
						//$html .= "$x,$y,";
						$points[] = $x;
						$points[] = $y;
					}
					$html .= implode(',', $points);
					$html .= '");';
			   } 
			
		$html .= '</script>';
		return $html;
		 
	}

	private function getComponent($path){
		$component = array();
		if(substr($path, 0,1)==':') return $component;


		if(isset($this->files['part.'.$path])){
			$path = 'part.'.basename($path);
			//if(!isset($this->files[$path])) return;
			$xmlPart = new SimpleXMLElement($this->files[$path]);
			$from = '';

		}else if(strpos($path,$this->coreIndicator)){
			$path = __DIR__.SLASH.substr($path, strpos($path, $this->coreIndicator)+1);
			if(!file_exists($path)) return $component ;
			$xmlPart = new SimpleXMLElement(file_get_contents($path));
			$from = __DIR__.SLASH.'fritzing-parts/svg/core/';
		}else if( file_exists( __DIR__.SLASH.'fritzing-parts/core/'.basename($path))){
			$path =  __DIR__.SLASH.'fritzing-parts/core/'.basename($path);
			$xmlPart = new SimpleXMLElement(file_get_contents($path));
			$from = __DIR__.SLASH.'fritzing-parts/svg/core/';
		}else{
			return $component ;
		}

			
		

		$component['name'] = (string)$xmlPart->title;
		$component['description'] = (string)$xmlPart->description;


		$partAttributes = $xmlPart->views->breadboardView->layers->attributes();
		if($from==''){
			$component['breadboard'] = $this->files['svg.'.str_replace('/', '.', $partAttributes['image'])];
		}else{
			$component['breadboard'] = file_get_contents($from.$partAttributes['image']);
		}
		return $component;
	}

}
?>