#!/usr/local/bin/php
<?php

$fh=fopen("settings.conf", "r");
while ($line=fgets($fh, 80)) {
  if (preg_match('/^[a-z]+=("[a-z0-9]+"|[0-9]+)$/', $line)) {
    $line_a=explode("=", $line);
    $conf[$line_a[0]]=trim($line_a[1],'"');
  }
}

print_r($conf);

extract($conf);

echo "$dbuser";
echo "$nodered";
