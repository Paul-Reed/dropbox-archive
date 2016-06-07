#!/usr/local/bin/php
<?php

$fh=fopen("settings.conf", "r");
while ($line=fgets($fh, 80)) {
  if (preg_match('/^[a-zA-Z_]+=("[a-zA-Z:.0-9_\/]+")/', $line)) {
  //  $line_a=explode("=", $line);
    $line_a=array_map('trim',explode("=", $line));
    $conf[$line_a[0]]=trim($line_a[1], '"');
  }
}

print_r($conf);

extract($conf);

echo "$dbuser\n";
echo "$emoncms_server\n";

