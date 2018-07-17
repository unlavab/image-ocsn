<?php

require 'vendor/autoload.php';

use Gregwar\Image\Image;

$source = file_get_contents("mon_id.json");
$input = json_decode($source, true);

$input['data'][$_POST['key']]['labeled'] = true;

$fp = fopen("mon_id.json", 'w');
fwrite($fp, json_encode($input, JSON_PRETTY_PRINT));
fclose($fp);

if (!$_POST['skip']) {
	$filename = "id_".$_POST['key'];
	Image::open($_FILES['croppedImage']['tmp_name'])
    ->resize(512, 512)
    ->save('output/'.$filename.'.png', 'jpg', 85);
}



