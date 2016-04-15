<?php
    /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.
    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project: http://openenergymonitor.org
    */

    $emoncms_server = "http://192.168.1.10/emoncms"; //emoncms local IP address
    $emoncms_apikey = ""; // Needs to be emoncms write API key
    $dbuser = "emoncms";    // Database user name - default is emoncms
    $dbpass = ""; // Database user password
    $dbname = "emoncms";    // Database name - default is emoncms
    $datadir = "/var/lib";  // path to emoncms data directories
    $store = "7"; // Number of days of archives to store
    // Create archive backups of node-red flows, configs and credentials
    $nodered = "Y"; // options Y or N
    $NRdir = "/home/pi/.node-red"; //Node-red backup dir, default is /home/pi/.node-red
