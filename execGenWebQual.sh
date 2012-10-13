#!/bin/bash
echo $1>>/tmp/log2
nohup ./genWebQual.sh $1 2>&1 >> /tmp/log2 &
return