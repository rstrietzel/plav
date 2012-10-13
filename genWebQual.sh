#!/bin/bash

folder=$1
destRoot=$2
size=$3
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

#embedded php ftw - get the size and directory of web quality images
ImagesDir=`$php -r 'include("config.php"); echo IMAGES_DIR;'`
wq=`$php -r 'include("config.php"); echo WEB_QUALITY;'`
dest=${destRoot}/${folder#$ImagesDir}

if [ !  -e "$dest" ] ; then mkdir -p $dest ; fi
if [ -e "${dest}/.lock" ] ; then
    echo "another instance is running in $dest, exiting"
    exit
fi

find ${dest} -maxdepth 1 -type f | while read file ; do
    sfile=${folder}/$(basename $file)
    if [ ! -e "$sfile" ] ; then
        rm "$sfile"
    fi
done
touch "${dest}/.lock" 
if [ $? -ne 0 ] ; then logger -s "plav: could not touch ${dest}/lock" ; exit 1; fi
    
find $folder -maxdepth 1 | while read item ; do
    ditem=${destRoot}/${item#$ImagesDir}
    mimetype=$(file -bi $item)
    if [ ${mimetype:0:5} == "image" -a ! -e $ditem ] ; then
        $IMConvert -auto-orient -resize $size -quality $wq $item $ditem
    fi
done

rm ${dest}/.lock
