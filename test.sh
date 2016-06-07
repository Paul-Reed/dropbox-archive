#!/bin/bash
eval `egrep '^[a-z]+=("[a-z0-9]+"|[0-9]+)$' settings.conf`

printf "dbus=%s\nbar=%s\nsomeint=%s\n\n" "$dbuser" "$dbpass" "$store"

echo "$dbuser"
