#!/bin/sh

mkdir -p web/vendor
rm -rf web/vendor/*
cp node_modules/moment/min/moment-with-locales.js web/vendor/moment-with-locales.js
cp node_modules/easy-autocomplete/dist/easy-autocomplete.min.css web/vendor/easy-autocomplete.min.css
cp node_modules/easy-autocomplete/dist/easy-autocomplete.themes.min.css web/vendor/easy-autocomplete.themes.min.css
cp node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.min.js web/vendor/jquery.easy-autocomplete.min.js
