<!-- Show Gallery here -->
<?php include("config.php")?>
<!doctype html>
<html>
    <head>
        <style>
            #galleria{ width: 100%; height: 100%; background: #000 }
            html, body {background: #000; width: 100%; height: 100%; padding:0; margin: 0;}
        </style>
        <script src="<?php echo JQUERY_URL?>"></script>
        <script src="galleria/galleria-1.2.8.min.js"></script>
    </head>
    <body>
        <div id="buttons">
            <a href="#" id="slideshow">Slideshow</a>
            <a href="#" id="fullscreen">Fullscreen</a>
            <a href="#" id="download">Zur Downloadliste hinzufuegen</a>
        </div>
        <div id="galleria">

<?php
//echo serialize("pics/testpics");
$dir=$_GET['dir'];
if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if (stripos($file, "jpg") !== false) {
            echo "<img src=\"".$dir."/".$file."\">";
        }
    }
    closedir($handle);
}
?>
        </div>
        <script>

                
            Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');

           Galleria.run('#galleria', {
                transition: 'fade',
                responsive: true
            });
           Galleria.ready(function() {
              var gallery = this; // galleria is ready and the gallery is assigned
              $('#fullscreen').click(function() {
                gallery.toggleFullscreen(); // toggles the fullscreen
              });
            $("#buttons").hover(function(){$(this).fadeTo(1,200);},function(){$(this).fadeTo(500,0.0001);});
            $(".galleria-thumbnails-container").hover(function(){$(this).fadeTo(1,200);},function(){$(this).fadeTo(500,0.0001);});
              
            });

            //Galleria.ready(function() {
            //   $('#galleria').data('galleria').enterFullscreen();
            //});
        </script>
    </body>
</html>