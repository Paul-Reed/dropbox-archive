<?php
// $output = exec("lib/./dropbox_uploader.sh -f /home/pi/.dropbox_uploader list");
$output = exec("lib/./dropbox_uploader.sh -f /home/pi/.dropbox_uploader list /emoncms-backup/ | awk 'NR!=1{ print $3 }'");
//echo "$output";
if in_array() {
    local hay needle=$1
    shift
    for hay; do
        [[ $hay == $needle ]] && return 0
    done
    return 1
}
print_r(array_values($output));

