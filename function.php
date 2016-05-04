<?php
function secondToTime($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
function app_autoloader($class_name) {
		require_once('class/'.$class_name.'.class.php');
}
function errorToException( $errno, $errstr, $errfile, $errline, $errcontext)
{
	if(strpos($errstr,'disk_')!==false) return;
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);   
}


function slugify($text)
	{ 
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\\pL\.\d]+~u', '-', $text);
	  // trim
	  $text = trim($text, '-');
	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  // lowercase
	  $text = strtolower($text);
	  // remove unwanted characters
	  $text = preg_replace('~[^-\.\w]+~', '', $text);

	  if (empty($text))
	    return 'n-a';
	  return $text;
}
function secure_user_vars($var){
	if(is_array($var)){
		$array = array();
		foreach($var as $key=>$value):
			$array[secure_user_vars($key)] = secure_user_vars($value);
		endforeach;
		return $array;
	}else{
		return htmlspecialchars($var, ENT_NOQUOTES, "UTF-8");
	}
}

function base64_to_image($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb"); 
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 
    return $output_file; 
}

function getExt($file){
	$ext = explode('.',$file);
	return strtolower(array_pop($ext));
}

function imageResize($image,$w,$h){
	$resource = imagecreatefromstring(file_get_contents($image));
	$size = getimagesize($image);
	$h = (($size[1] * (($w)/$size[0])));
	$thumbnail = imagecreatetruecolor($w , $h);
	imagecopyresampled($thumbnail ,$resource, 0,0, 0,0, $w, $h, $size[0],$size[1]);
	imagedestroy($resource);
	imagejpeg($thumbnail , $image, 100);
}

?>