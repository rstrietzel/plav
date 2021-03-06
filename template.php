<!DOCTYPE html>
<html>
<head>
<?php 
$server= $_SERVER['SERVER_NAME'];
//if no image found, start a gallery here
if ( $folderCount==0 ) {
    header("Location://${server}${rootUrl}show.php?dir=".WEB_QUALITY_DIR."$simplePath");

    exit();
    }
?>
<title> <?php echo $realDir ?> </title>
<style type="text/css">
body {
	margin-top: 0;
	font-family: sans-serif;
}
img {
	border: 0;
}
a {
	text-decoration: none;
}
.square {
	display: inline-block;
}
.image, .foldername, .image_nopreview, .foldername_nopreview {
	display: table-cell;
	vertical-align: middle;
}
.image, .image_nopreview {
	width: <?php echo THUMB_SIZE ?>px;
	text-align: center;
}
.image, .foldername {
	height: <?php echo THUMB_SIZE ?>px;
}
.foldername, .foldername_nopreview {
	padding-left: 1ex;
}
#parentfolder {
	font-size: 4em;
	font-weight: bold;
	height: 0.6em;
}
#credit {
	text-align: right;
	font-size: 0.25cm;
	color: gray;
}
.working{
	background-color: gray;
}
.working a{
	text-decoration: line-through;
}
</style>
<script src="<?php echo JQUERY_URL?>"></script>
<script type="text/javascript">

function isWorking(url){
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}
function checkfolders(){
	var refresh = false;

	$(".folder").each(function(index) {
		if(isWorking("../"+$(this).attr('id')+'/.lock')){
			$(this).addClass("working");
			refresh = 1;
		}
		else {
	        $(this).removeClass("working");
		}
		});
		if(refresh == 1) setTimeout(checkfolders,800);

	}
$(function() {
	checkfolders();
});

</script>
<?php foreach ($plugins as $p) if (is_file("plugins/$p/style.css")) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $rootUrl."plugins/$p/style.css" ?>" />
<?php } ?>
</head>
<body>
<div id="parentfolder"><a href="<?php echo $parentLink ?>">
<?php if ($parentLink !== '') { ?>
^
<?php } ?>
&nbsp;</a></div>

<?php plugins_include("before_content.php") ?>

<?php foreach($folders as $folder) { $preview = getAlbumPreview($folder["file"]); ?>
	<div class="folder" id="<?php echo WEB_QUALITY_DIR."/".$simplePath.$folder["name"]; ?>">
	<?php if ($preview === "") { ?>
		<div class="square"><div class="image_nopreview"> - </div></div>
		<div class="square"><div class="foldername_nopreview"> <a href="<?php echo $folder["link"] ?>"><?php echo $folder["name"] ?></a> </div></div>
	<?php } else { ?>
		<div class="square"><div class="image"> <a href="<?php echo $folder["link"] ?>"><img src="<?php echo $rootUrl.$preview ?>" /></a> </div></div>
		<div class="square"><div class="foldername"> <a href="<?php echo $folder["link"] ?>"><?php echo $folder["name"] ?></a> </div></div>
		<?php if (isset($generating)) { ob_flush(); flush(); } ?>
	<?php } ?>
	</div>
<?php } ?>

<?php foreach ($otherFiles as $file) { ?>
	<div class="miscfile"><a href="<?php echo $file["link"] ?>"><?php echo $file["name"] ?></a></div>
<?php } ?>

<?php plugins_include("after_content.php") ?>

<p id="credit">
Generated by <a href="http://www.positon.org/bizou/">Bizou</a>
</p>
</body>
</html>
