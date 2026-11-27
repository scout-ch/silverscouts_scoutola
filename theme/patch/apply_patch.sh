#!/bin/bash

source_dir="../base"
target_dir="../merged"

while [[ $# -gt 0 ]]; do
    case "$1" in
        --source)
            source_dir="$2"
            shift 2
            ;;
        --target)
            target_dir="$2"
            shift 2
            ;;
        *)
            echo "Unknown argument: $1"
            exit 1
            ;;
    esac
done

if [[ ! -d "$source_dir" ]]; then
    echo "Source directory does not exist: $source_dir"
    exit 1
fi

script_dir="$(cd "$(dirname "$0")" && pwd)"

if [[ "$source_dir" != "$target_dir" ]]; then
    rm -rf "$target_dir"
    cp -RL "$source_dir" "$target_dir"
    cp -rf "$script_dir/." "$target_dir/"
fi

cd "$target_dir"
find . -type f -name '*.patch' | while read patch_file; do
    target_file="${patch_file%.patch}"
    patch --merge --verbose "$target_file" < "$patch_file"
    rm "$patch_file"
done
rm ./apply_patch.sh ./create_patch.sh
