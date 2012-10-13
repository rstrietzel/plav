<?php
include('config.php');
$type = 'application/zip';
$destination = '/tmp/dl.zip';
$downloadlist = explode(",",$_COOKIE["downloadlist"]) ;
$overwrite = true;
foreach ($downloadlist as $index => $file) {
	$file = explode("/", $file);
	$file[0] = IMAGES_DIR;
	$file[count($file)-1] = implode(".",array_slice(explode(".",$file[count($file)-1]), 1));
	//print_r($file);
	$downloadlist[$index] = implode("/", $file);
}

if(file_exists($destination) && !$overwrite) { return false; }
if(is_array($downloadlist)) {
	foreach($downloadlist as $index => $file) {
		if(!file_exists($file)) {
			unset($downloadlist[$index]);
		}
	}
}
//print_r($downloadlist);

if(count($downloadlist)) {
	$zip = new ZipArchive();
	if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
		echo "weird stuff";
	}
	foreach($downloadlist as $file) {
		$zip->addFile($file,$file);
	}
	$zip->close();
	header("Content-Type: $type");
    header("Content-Disposition: attachment; filename=\"$destination\"");
    readfile($destination);
}
else
{
	echo "err0r.";
}



?>
</pre>
</html>