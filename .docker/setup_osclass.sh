#!/bin/bash
set -e

VERSION="latest"
REMOVE_ZIP=false

for arg in "$@"; do
    [ "$arg" = "--rm" ] && REMOVE_ZIP=true || VERSION="$arg"
done

OSCLASS_ZIP="osclass-$VERSION.zip"

if [ "$VERSION" = "latest" ]; then
    OSCLASS_URL="https://sourceforge.net/projects/osclass-by-osclasspoint/files/latest/download"
else
    OSCLASS_URL="https://sourceforge.net/projects/osclass-by-osclasspoint/files/${VERSION}/osclass-${VERSION}.zip/download"
fi

if [ ! -f "$OSCLASS_ZIP" ]; then
    curl -L "$OSCLASS_URL" -o "$OSCLASS_ZIP"
fi

unzip -o "$OSCLASS_ZIP"

if [ "$REMOVE_ZIP" = true ]; then
    rm "$OSCLASS_ZIP"
fi
