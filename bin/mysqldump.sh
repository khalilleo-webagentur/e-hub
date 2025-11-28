#!/bin/bash

date=$(date +"%d%m%Y")
mysqldump -u root -p e_hub > dump_$date.sql
