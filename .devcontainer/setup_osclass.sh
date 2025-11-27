#!/bin/bash
# Download and unpack the latest Osclass release into the workspace root

set -e

OSCLASS_URL="https://sourceforge.net/projects/osclass-by-osclasspoint/files/latest/download"
OSCLASS_ZIP="osclass-latest.zip"

cd /workspace
curl -L "$OSCLASS_URL" -o "$OSCLASS_ZIP"
unzip -o "$OSCLASS_ZIP"
rm "$OSCLASS_ZIP"

ln -sf ../../plugins/oidc ./oc-content/plugins/oidc
ln -sf ../../plugins/pbs ./oc-content/plugins/pbs
