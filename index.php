<?php

require 'config.php';

// global variables, globals should remain contant
$scriptUrl = $_SERVER["SCRIPT_NAME"];
$rootUrl = dirname($scriptUrl);
if (substr($rootUrl, -1) !== '/') $rootUrl.='/';  // add a trailing / to rootUrl
// $scriptUrl =  "/path/to/bizou/index.php"
// $rootUrl =  "/path/to/bizou/"

require 'functions.php';


// if url == http://localhost/photos/index.php/toto/titi, path_info == /toto/titi
// if url == http://localhost/photos/index.php, path_info is not set
// if url == http://localhost/photos/, path_info is not set
// if path_info is not set, we are at top level, so we redirect to /photos/index.php/
if (! isset($_SERVER["PATH_INFO"])) {
	header("Location: $scriptUrl/");
	exit();
}

// simplePath is the simple path to the directory
// extract /path/to/dir/ from /index.php/path/to/dir/
$simplePath = getPathInfo();

# realDir is the directory in filesystem
# seen from current script directory
$realDir = IMAGES_DIR.$simplePath;

if (! is_dir($realDir)) {
	header("HTTP/1.1 404 Not Found");
	die("Directory Not Found");
}



// init folders with the . folder
$folders = array();
$imageFiles = array();
$otherFiles = array();
$folderCount = 0;
$imageCount = 0;


foreach (scandir($realDir) as $file) if ($file != '.' and $file != '..')
{
	if (is_dir("$realDir/$file"))
	{
		$folders[] = array( "name" => $file, "file" => "$realDir/$file", "link" => "$scriptUrl$simplePath/$file" );
                $folderCount++;
                exec("exec nohup setsid ./genWebQual.sh  $realDir/$file ".THUMB_DIR." ".THUMB_SIZE." >> /tmp/log1 2>&1 & sleep 0.1");
                exec("exec nohup setsid ./genWebQual.sh  $realDir/$file ".WEB_QUALITY_DIR." ".WEB_SIZE." >> /tmp/log1 2>&1 & sleep 0.1");
	}
	else
	{
		$ext = strtolower(substr($file, -4));
		if ($ext == ".jpg" or $ext == ".png") {
			$imageFiles[] = array( "name" => $file, "file" => "$realDir/$file", "link" => getImageLink("$simplePath/$file") );
                        $imageCount++;
		} 
                else {
			$otherFiles[] = array( "name" => $file, "link" => "$rootUrl$realDir/$file" );
		}
	}
}

if ( $imageCount > 0 ){
    $folders[] = array( 
    "name" => "Start Galleria here", 
    "file" => "$realDir",
    "link" => "${rootUrl}show.php?dir=".WEB_QUALITY_DIR."$simplePath");
    exec("exec nohup setsid ./genWebQual.sh  $realDir ".THUMB_DIR." ".THUMB_SIZE." >> /tmp/log1 2>&1 & sleep 0.1");
    exec("exec nohup setsid ./genWebQual.sh  $realDir ".WEB_QUALITY_DIR." ".WEB_SIZE." >> /tmp/log1 2>&1 & sleep 0.1");
}

if (dirname($simplePath) !== '')
	$parentLink = $scriptUrl.dirname($simplePath);
else
	$parentLink = "";


///// template starts here /////
header('Content-Type: text/html; charset=utf-8');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));

require 'template.php';

?>
