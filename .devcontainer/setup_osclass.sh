#!/bin/bash
# Download and unpack the latest Osclass release into the workspace root

set -e

OSCLASS_URL="https://sourceforge.net/projects/osclass-by-osclasspoint/files/latest/download"
OSCLASS_ZIP="osclass-latest.zip"

cd /workspace
# curl -L "$OSCLASS_URL" -o "$OSCLASS_ZIP"
unzip -o "$OSCLASS_ZIP"
# rm "$OSCLASS_ZIP"

rm -rf ./oc-content/plugins/oidc || true
ln -sf ../../plugins/oidc ./oc-content/plugins/oidc

rm -rf ./oc-content/plugins/silverscouts || true
ln -sf ../../plugins/silverscouts ./oc-content/plugins/silverscouts

rm -rf ./oc-content/themes/sigma.orig
cp -r ./oc-content/themes/sigma ./oc-content/themes/sigma.orig
rm -rf ./theme/base
cp -r ./oc-content/themes/sigma ./theme/base

rm -rf ./oc-content/themes/sigma  || true
ln -sf ../../theme/merged ./oc-content/themes/sigma
