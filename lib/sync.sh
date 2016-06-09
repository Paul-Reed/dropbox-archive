#!/bin/bash

date=$(date +"%d_%m_%Y_%H%M")
DIR="$(dirname $(readlink -f $0))"
cd $DIR
pwd

# Import user settings
eval `egrep '^([a-zA-Z_]+)="(.*)"' ../settings.conf`

mkdir -p ../temp_data/mysql

# Dump DB file
# mkdir ../temp_data/mysql
mysqldump --lock-tables --user=$dbuser --password=$dbpass $dbname > ../temp_data/mysql/$dbname-$date.sql

# Prepare emoncms data archive and delete original
tar -czf ../backups/archive_$date.tar.gz -C ../temp_data/ .
rm -rf ../temp_data

# Upload the file to Dropbox
./dropbox_uploader.sh -sf /home/pi/.dropbox_uploader upload ../backups/ /

# Delete expired local archive files
cd ../backups
find . *.tar.gz -mtime +$store -exec rm {} \;
# find $DIR/backups/*.tar.gz -mtime +$store -exec rm {} \;

# Remove old Dropbox backups
pwd
backups=($(find *.gz)) # Array of current backups

dropboxfiles=($(../lib/./dropbox_uploader.sh -f /home/pi/.dropbox_uploader list /backups/ | awk 'NR!=1{ print $3 }')) # Array of Dropbox files

in_array() {
    local hay needle=$1
    shift
    for hay; do
        [[ $hay == $needle ]] && return 0
    done
    return 1
}

for i in "${dropboxfiles[@]}"
do
	in_array $i "${backups[@]}" && echo 'Keeping ' $i || ../lib/./dropbox_uploader.sh -f /home/pi/.dropbox_uploader delete /backups/$i
done
