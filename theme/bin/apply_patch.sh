#!/bin/bash

rm -rf ./merged/
cp -RL ./base ./merged
cd ./merged

cp -rf ../patch/. ./
find . -type f -name '*.patch' | while read patch_file; do
    target_file="${patch_file%.patch}"
    patch --merge --verbose "$target_file" < "$patch_file"
    rm "$patch_file"
done
