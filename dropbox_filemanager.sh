#!/bin/bash

# Emoncms Database Credentials
dbuser="emoncms" # Database user name - default is emoncms
dbpass=""        # Database user password
dbname="emoncms" # Database name - default is emoncms

# Number of days of archives to store
store=7

# General Config
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
datadir="/var/lib" #path to emoncms data directories
date=$(date +"%Y-%m-%d_%H%M%S")
dropboxconfig=".dropbox_uploader"

# A few checks first...
test -e /home/pi/.dropbox_uploader || $DIR/./dropbox_uploader.sh
test -e ! $DIR/temp/.gitignore || find $DIR -name .gitignore rm -f {} \;

# Stop emonhub
sudo service emonhub stop

# Copy emoncms data directories to temp directory
cp -R $datadir/phpfiwa $DIR/temp
cp -R $datadir/phpfina $DIR/temp
cp -R $datadir/phptimeseries $DIR/temp

# Dump DB file
mkdir $DIR/temp/mysql
mysqldump --lock-tables --user=$dbuser --password=$dbpass $dbname > $DIR/temp/mysql/$dbname-$date.sql

# Start emonhub
sudo service emonhub start

# Prepare emoncms data archive and delete original
tar cf - $DIR/temp/ | gzip > $DIR/emoncms-backup/$dbname-$date.tar.gz
rm -rf $DIR/temp/*

# Upload the file to Dropbox
# Requires [Dropbox Uploader](https://github.com/andreafabrizi/Dropbox-Uploader)
$DIR/./dropbox_uploader.sh -sf /home/pi/$dropboxconfig upload $DIR/emoncms-backup/ /

# Delete .sql files older than 5 days
find $DIR/emoncms-backup/*.tar.gz -mtime +$store -exec rm {} \;

# Remove old Dropbox backups
cd $DIR/emoncms-backup
backups=($(find *.gz)) # Array of current backups
dropboxfiles=($($DIR/./dropbox_uploader.sh -f /home/pi/$dropboxconfig list /emoncms-backup/ | awk 'NR!=1{ print $3 }')) # Array of Dropbox files

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
	in_array $i "${backups[@]}" && echo 'Keeping ' $i || $DIR/./dropbox_uploader.sh -f /home/pi/$dropboxconfig delete /emoncms-backup/$i
done
