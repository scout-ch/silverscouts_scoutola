#!/bin/bash

rm -rf ./merged/
cp -RL ./base ./merged
cd ./merged

cp -rf ../patch/* ./
patch --merge --verbose  -p1 < ./theme.patch
rm ./theme.patch
