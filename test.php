#!/usr/local/bin/php
<?php

$fh=fopen("settings.conf", "r");
$pattern='/^(\w+)="([\w\/\.\:]+)"/';
while ($line=fgets($fh, 80)) {

if (preg_match($pattern, $line, $match)) {
     $conf[$match[1]]=$match[2];
     }
}

print_r($conf);

extract($conf);

echo "$dbuser\n";
echo "$emoncms_server\n";

