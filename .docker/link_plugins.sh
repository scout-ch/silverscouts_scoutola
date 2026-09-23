#!/bin/bash
# Download and unpack the latest Osclass release into the workspace root

set -e

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
