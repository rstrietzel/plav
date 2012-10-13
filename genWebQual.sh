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

#embedded php ftw - get the size and directory of web quality images
size=`$php -r 'include("config.php"); echo WEB_SIZE;'`
wqd=`$php -r 'include("config.php"); echo WEB_QUALITY_DIR;'`

if [ !  -e ${wqd}/$folder ] ; then mkdir -p ${wqd}/$folder ; fi
if [ -e ${wqd}/.lock ] ; then
	echo another instance is running in $wqd, exiting
	exit
fi
touch ${wqd}/.lock

for item in ${folder}/* ; do
	mimetype=$(file -bi $item)
	if [ ${mimetype:0:5} == "image" -a ! -e ${wqd}/$item ] ; then
		$IMConvert -resize $size $item ${wqd}/$item ;
	fi
done

rm ${wqd}/.lock
