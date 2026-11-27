#!/bin/bash

cd ./merged

# Create patch files for each file that differs between base and merged
find . -type f -name '*.php' | while read file; do
    file="${file#./}"
    
    if [[ -f "../base/$file" ]]; then
        if ! diff -q "../base/$file" "$file" > /dev/null 2>&1; then
            # Files differ, create individual patch
            mkdir -p "../patch/$(dirname "$file")"
            diff -Naur "../base/$file" "$file" > "../patch/${file}.patch"
        fi
    fi
done
