#!/bin/bash

rm -f infinetia.sql
rm -f infinetia.tar.gz
mysqldump -u root -ppw spiclickadmin > infinetia.sql
tar -cvf infinetia.tar infinetia.sql
gzip infinetia.tar
rm -f infinetia.sql
