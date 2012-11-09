<!-- Show Gallery here -->
<?php include("config.php"); ?>
<!doctype html>
<html>
    <head>
        <style>
            #galleria{ width: 100%; height: 100%; background: #000 }
            html, body {background: #000; width: 100%; height: 100%; padding:0; margin: 0;}
        </style>
        <script src="<?php echo JQUERY_URL?>"></script>
        <script src="galleria/galleria-1.2.8.min.js"></script>
        <script type="text/javascript">
        function setCookie(c_name,value,exdays)
            {
            var exdate=new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
            document.cookie=c_name + "=" + c_value;
            }

        function getCookie(c_name)
            {
            var i,x,y,ARRcookies=document.cookie.split(";");
            for (i=0;i<ARRcookies.length;i++)
            {
              x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
              y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
              x=x.replace(/^\s+|\s+$/g,"");
              if (x==c_name)
                {
                return unescape(y);
                }
              }
            }            
        </script>
    </head>
    <body>
        <div id="buttons">
            <a href="#" id="slideshow">Slideshow</a>
            <a href="#" id="fullscreen">Fullscreen</a>
            <a href="#" id="download">Auf Downloadliste</a>
            <a href="download.php" target="_blank">Markierte downloaden</a>

        </div>
        <div id="galleria">

        <?php
        $dir=$_GET['dir'];
        $images = array();
        if($files = scandir($dir)){
            foreach ($files as $key => $file) {
                if (stripos($file, "jpg") !== false) {
                    $exif = exif_read_data ($dir."/".$file);
                    $images[$exif['DateTime']] = $file;
                }            
            }
        }
        foreach ($images as $time => $image) {
            $thumbdir = THUMB_DIR."/".implode('/', array_slice(explode('/', $dir), 1))."/".$image;
            echo "<a href=\"".$dir."/".$image."\"><img src=\"".$thumbdir."\"></a>";
        }

        /*
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if (stripos($file, "jpg") !== false) {
                    $thumbdir = THUMB_DIR."/".implode('/', array_slice(explode('/', $dir), 1))."/".$file;
                    echo "<a href=\"".$dir."/".$file."\"><img src=\"".$thumbdir."\"></a>";
                }
            }
            closedir($handle);
        }
        */
        ?>
        </div>
        <script>
        var downloadlist;
                
            Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');

           Galleria.run('#galleria', {
                transition: 'fade',
                responsive: true
            });
           Galleria.ready(function() {
                var gallery = this; // galleria is ready and the gallery is assigned
                $('#slideshow').click(function() {
                        gallery.playToggle(); // toggles the slideshow
                });

                $('#fullscreen').click(function() {
                        gallery.toggleFullscreen(); // toggles the fullscreen
                });
                //autohide buttons and thumblist
                $("#buttons").hover(function(){$(this).fadeTo(1,200);},function(){$(this).fadeTo(500,0.0001);});
                $(".galleria-thumbnails-container").hover(function(){$(this).fadeTo(1,200);},function(){$(this).fadeTo(500,0.0001);});
            //downloadlist refresh
                this.bind("image", function(e) {
                    var cookie = getCookie("downloadlist");
                    if(cookie != null && cookie != ""){
                        var downloadlist = cookie.split(',');
                        Galleria.log(downloadlist);
                        if($.inArray(e.galleriaData.image, downloadlist) != "-1" ){
                            $('#download').addClass('checked');
                        }
                        else {
                            $('#download').removeClass('checked');
                        }
                    }


                    Galleria.log(e.galleriaData.image);
                });
                $('#download').click(function() {
                    var e = gallery.getData();
                    Galleria.log(e);
                    var cookie = getCookie("downloadlist");
                    if(cookie != null && cookie != ""){
                        var downloadlist = cookie.split(',');
                        if($.inArray(e.image, downloadlist) != "-1"){
                            downloadlist.splice($.inArray(e.image, downloadlist), 1);
                            $('#download').removeClass('checked');

                        }
                        else {
                            downloadlist.push(e.image);
                            $('#download').addClass('checked');

                        }
                    }
                    else{
                        var downloadlist = e.image;
                        $('#download').addClass('checked');

                    }            
                    setCookie("downloadlist",downloadlist,3);

                });
            });

            //Galleria.ready(function() {
            //   $('#galleria').data('galleria').enterFullscreen();
            //});
        </script>
    </body>
</html>