#!/bin/bash

folder=$1
php=`which php`
if [ $? != 0 ] ; then
    logger -s "plav: php not found"
    exit 1
fi

IMConvert=`which convert`
if [ $? != 0 ] ; then
    logger -s "plav: ImageMagick's convert not found"
    exit 1
fi
#echo $folder >> /tmp/folder
#echo `pwd`>> /tmp/folder

#embedded php ftw - get the size and directory of web quality images
size=`$php -r 'include("config.php"); echo WEB_SIZE;'`
ImagesDir=`$php -r 'include("config.php"); echo IMAGES_DIR;'`
wqd=`$php -r 'include("config.php"); echo WEB_QUALITY_DIR;'`
wq=`$php -r 'include("config.php"); echo WEB_QUALITY;'`
dest=${wqd}/${folder#$ImagesDir}

if [ !  -e "$dest" ] ; then mkdir -p $dest ; fi
if [ -e ${dest}/.lock ] ; then
	echo $folder >> /tmp/log3
    echo another instance is running in $wqd, exiting
    exit
fi
touch ${dest}/.lock
for file in ${dest}/* ; do
    sfile=${folder}$(basename $file)
    if [ ! -e "$sfile" ] ; 
        then rm $file
    fi
done
    

for item in ${folder}/* ; do
    ditem=${wqd}/${item#$ImagesDir}
    mimetype=$(file -bi $item)
    if [ ${mimetype:0:5} == "image" -a ! -e $ditem ] ; then
        $IMConvert -resize $size -quality $wq $item $ditem ;
    fi
done

rm ${dest}/.lock
