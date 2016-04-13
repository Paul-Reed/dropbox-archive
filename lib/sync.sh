#!/bin/bash

# Remove old Dropbox backups
DIR="$(dirname $(readlink -f $0))"
pwd
cd $DIR/../backups
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
