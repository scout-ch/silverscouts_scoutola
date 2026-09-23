#!/bin/bash

cd ./merged

# Create patch files for each file that differs between base and merged
find . -type f | while read file; do
    # Remove leading ./
    file="${file#./}"
    
    # Compare with base directory
    if [[ -f "../base/$file" ]]; then
        if ! diff -q "../base/$file" "$file" > /dev/null 2>&1; then
            # Files differ, create individual patch
            mkdir -p "../patch/$(dirname "$file")"
            diff -Naur "../base/$file" "$file" > "../patch/${file}.patch"
            echo "Created patch: ../patch/${file}.patch"
        fi
    else
        # File only exists in merged, create patch
        mkdir -p "../patch/$(dirname "$file")"
        diff -Naur /dev/null "$file" > "../patch/${file}.patch"
        echo "Created patch: ../patch/${file}.patch (new file)"
    fi
done

echo "Patch files created in ../patch/"
