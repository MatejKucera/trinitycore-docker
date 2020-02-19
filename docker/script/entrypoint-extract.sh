#!/bin/sh

cp -f /wow/bin/mapextractor /wowdir/mapextractor
cp -f /wow/bin/mmaps_generator /wowdir/mmaps_generator
cp -f /wow/bin/vmap4extractor /wowdir/vmap4extractor
cp -f /wow/bin/vmap4assembler /wowdir/vmap4assembler

cd /wowdir
ls -la

rm -rf maps
rm -rf dbc
rm -rf vmaps
rm -rf mmaps

/wowdir/mapextractor
/wowdir/vmap4extractor
/wowdir/vmap4assembler
/wowdir/mmaps_generator