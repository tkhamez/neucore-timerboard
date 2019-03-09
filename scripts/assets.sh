#!/bin/sh

mkdir -p web/vendor
rm -rf web/vendor/*
cp node_modules/jquery/dist/jquery.slim.min.js web/vendor/jquery.slim.min.js
cp node_modules/popper.js/dist/umd/popper.min.js web/vendor/popper.min.js
cp node_modules/bootstrap/dist/js/bootstrap.min.js web/vendor/bootstrap.min.js
cp node_modules/moment/min/moment-with-locales.js web/vendor/moment-with-locales.js
cp node_modules/easy-autocomplete/dist/easy-autocomplete.min.css web/vendor/easy-autocomplete.min.css
cp node_modules/easy-autocomplete/dist/easy-autocomplete.themes.min.css web/vendor/easy-autocomplete.themes.min.css
cp node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.min.js web/vendor/jquery.easy-autocomplete.min.js
