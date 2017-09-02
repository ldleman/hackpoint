<html>
<head>
	<style>


	</style>
</head>
<body>
<?php

$file = "C:\\Users\\Idleman\\Desktop\\test.fzz";

$zip = new ZipArchive;
$res = $zip->open($file);
$properties = array();

if ($res !== TRUE)  throw new Exception('Impossible d\'ouvrir le ZIP, code:' . $res);
$xmlString = $zip->getFromName(str_replace('.fzz','',basename($file)).'.fz');
$zip->close();



$xml = new SimpleXMLElement($xmlString);

$parts = array();

$minY = 0;

foreach($xml->instances->instance as $instance){
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


		if(isset($geometry['wireFlags']))
			$part['type'] = 'wire';

		foreach ($geometry as $key => $value) {
			if(substr($key, 0,1) == 'x') $part['geometry']['x'][] =(float)$value;
			if(substr($key, 0,1) == 'y') $part['geometry']['y'][] =(float)$value;
		}
		
	
		$part['geometry']['z'] =(float)$geometry['z'];

		if($minY>$part['geometry']['y'][0])$minY = $part['geometry']['y'][0];
		
		if(	$part['type']== 'wire'){
			$wireAttributes = $instance->views->breadboardView->wireExtras->attributes();
			$part['color'] = (string)$wireAttributes['color'];
			
			//var_dump($part['geometry']);
		
		}
	}
	$path = explode('/fritzing-parts/', $attributes->path);
	if(count($path)>1){
		$path = $path[1];

		//echo $path.'<br>';
		$path = 'fritzing-parts/'.$path;
		$part['fzp'] = $path;
		$xmlPart = new SimpleXMLElement(file_get_contents($path));
		$part['component'] = array(
			'title' => (string) $xmlPart->title,
			'description' => (string)$xmlPart->description,
			);
		$part['component']['name'] = (string)$xmlPart->title;
		$part['component']['description'] = (string)$xmlPart->description;

		$partAttributes = $xmlPart->views->iconView->layers->attributes();
		$icon = (string)$partAttributes['image'];
		$part['component']['icon'] = 'fritzing-parts/svg/core/'.$icon;

		$partAttributes = $xmlPart->views->breadboardView->layers->attributes();
		$icon = (string)$partAttributes['image'];
		$part['component']['breadboard'] = 'fritzing-parts/svg/core/'.$icon;

		$partAttributes = $xmlPart->views->schematicView->layers->attributes();
		$icon = (string)$partAttributes['image'];
		$part['component']['schema'] = 'fritzing-parts/svg/core/'.$icon;


	}


	$parts[] = $part;
}

$minY = abs($minY);
?>

<svg id="vis"></svg>

<script src="https://d3js.org/d3.v4.min.js"></script>

<script>
	 var vis = d3.select("#vis")
        .attr("width", 500)
        .attr("x", 500)
        .attr("y", 500)
        .attr("height", 500);


        <?php foreach ($parts as $part) { 
		if( $part['type']!='wire' ) continue;

			echo 'var line = vis.append("polyline")'."\n\t\t".'.attr("stroke-linejoin","round").style("stroke", \''.$part['color'].'\').attr("stroke-width", 5)'."\n\t\t";
			$x = 0;
			$y = 0;
			echo '.attr("points","';
			for ($i=0;$i<count($part['geometry']['x']);$i++) {
				$x += $part['geometry']['x'][$i];
				$y += $part['geometry']['y'][$i];
				if($i==0) $y+= $minY;
				echo "$x,$y,";
			}
			echo '");';
	    } ?>


	<?php foreach ($parts as $part) { 
		if(!isset($part['component']) || $part['type']!='component' ) continue;
	?>

	d3.xml('<?php echo $part['component']['breadboard']?>').mimeType("image/svg+xml").get(function(error, xml) {
	  if (error) throw error;
	  xml.documentElement.id = '<?php echo $part['sigle']?>';
	  document.getElementById('vis').appendChild(xml.documentElement);
	  d3.select("#<?php echo $part['sigle'];?>").attr("y", <?php echo $part['geometry']['y'][0]+$minY ?>)
	  .attr("x", <?php echo $part['geometry']['x'][0] ?>);
	});
	<?php } ?>
</script>
</body>
</html>