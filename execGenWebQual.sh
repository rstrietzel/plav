#!/bin/bash

./genWebQual.sh $1 2>&1 >/dev/zero \& ; disown %1"