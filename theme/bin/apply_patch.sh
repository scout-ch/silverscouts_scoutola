#!/bin/bash

rm -rf ./merged/
cp -RL ./base ./merged
cp -rf ./patch/* ./merged/
patch --merge --verbose  -p1 < ./merged/theme.patch
rm ./merged/theme.patch
