#!/bin/bash


rm -rf ./merged/
cp -Tr ../oc-content/themes/sigma.orig ./merged
cp -Trf ./overlay/ ./merged/
cd ./merged
patch -p1 < theme.patch
rm theme.patch
