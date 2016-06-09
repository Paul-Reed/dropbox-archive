#!/bin/bash
eval `egrep '^([a-zA-Z_]+)="(.*)"' settings.conf`

echo "$dbuser"
echo "$emoncms_server"
echo "$NRdir"
echo "$store"

