#!/bin/bash


diff -ruN ./base/ ./translated/ | grep -e "^-msgstr" -e "^+msgstr" -e "^+++"
