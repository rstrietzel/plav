This is plav, the Photo Library And Viewer. plav is a web gallery that:
- displays fullscreen
- prefetches images
- makes a reduced size web view of images
- makes original pictures available for download
- folders -> galleries

Install requirements:
- a webserver: tested with apache2 and lighttpd
- php5 (including cli) + php5-gd
- imagemagick
- preferably: a local jquery installation. on debian/ubuntu-like system this 
means libjs-jquery + javascript-common

just get the files and run setup.sh. This will create directories and set 
permissions in plav's root directory. It assumes www-data is the user that runs
the webserver.

plav is based on:
- bizou by Marc Maurice (http://www.positon.org/bizou/en.html - GPLv3+)
- Galleria by aino (http://galleria.io/ - MIT license)

plav itself is free software under the terms of the GPLv3+, except of the 
Galleria part, which is MIT licensed.