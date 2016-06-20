<?php
    /*

    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    */

    include "lib/metadata.php";
    include "lib/mysql.php";
    include "lib/phptimeseries.php";
    include "lib/phptimestore.php";
    include "lib/phpfiwa.php";
    include "lib/phpfina.php";
    include "lib/inputs.php";

    // Import user settings
    $fh=fopen("settings.conf", "r");
    $pattern='/^(\w+)="([\w\/\.\-\:]+)"/';
    while ($line=fgets($fh, 80)) {

    if (preg_match($pattern, $line, $match)) {
         $conf[$match[1]]=$match[2];
         }
    }
    print_r($conf);
    extract($conf);

    // Locate where script is installed

    $cdir = realpath(dirname(__FILE__)); // Current File Directory
    $tdir = "temp_data";                 // Temporary data Directory
    chdir ("$cdir");                     // Move current working directory

    // On first run enter dropbox configuration

    if (!file_exists("/home/pi/.dropbox_uploader")) {
    echo "\n\n\n";
    echo "Before using this script, it is necessary to\n";
    echo "configure your Dropbox API to allow backups\n";
    echo "to be uploaded.\n";
    echo "To configure the script, run;\n\n";
    echo "$cdir/lib/./dropbox_uploader.sh\n\n";
    echo "and follow the prompts.\n\n\n";
    die;
    }

    // Create temp Directory structure

    $Createdir = array(
      "$tdir/phpfina",
      "$tdir/phpfiwa",
      "$tdir/phptimeseries",
      "$tdir/phptimestore",
      "$tdir/mysql",
      "$tdir/nodered"
    );
    $permissions = 0755;
    foreach ($Createdir as $dir) {
      mkdir($dir, $permissions, TRUE);
    }

    if (!file_exists("backups")) {
    mkdir("backups", 0777, true);
    }

    $date = date("d-m-Y_Hi");

    //Set up emoncms  backup directory array

    $engines = array(
        'phpfiwa'=>array(
            'datadir'=> "$tdir/phpfiwa/"
         ),
        'phpfina'=>array(
            'datadir'=> "$tdir/phpfina/"
         ),
        'phptimeseries'=>array(
            'datadir'=> "$tdir/phptimeseries/"
         ),
        'phptimestore'=>array(
            'datadir'=> "$tdir/timestore/"
         )
    );

    $mysqli = false;
    $redis = false;

    // Fetch remote server feed list

    $feeds = file_get_contents($emoncms_server."/feed/list.json?apikey=$emoncmsapikey");
    $feeds = json_decode($feeds);

    $number_of_feeds = count($feeds);
    echo $number_of_feeds." Emoncms feeds found\n";

    if ($number_of_feeds==0) {
        echo "No feeds found at remote account\n";
        die;
    }

    foreach ($feeds as $feed)
    {

        if ($feed->engine==0 && $mysqli) {
            import_mysql($feed,$emoncms_server,$emoncmsapikey,$mysqli);
        }

        if ($feed->engine==1 && $feed->datatype==1) {
            import_phptimestore($feed->id,$emoncms_server,$emoncmsapikey,$engines['phptimestore']['datadir']);
        }

        if ($feed->engine==2) {
            import_phptimeseries($feed->id,$emoncms_server,$emoncmsapikey,$engines['phptimeseries']['datadir']);
        }

        if ($feed->engine==5) {
            import_phpfina($feed->id,$emoncms_server,$emoncmsapikey,$engines['phpfina']['datadir']);
        }

        if ($feed->engine==6) {
            import_phpfiwa($feed->id,$emoncms_server,$emoncmsapikey,$engines['phpfiwa']['datadir']);
        }

        if ($feed->engine==4 && $feed->datatype==1) {
            import_phptimestore($feed->id,$emoncms_server,$emoncmsapikey,$engines['phptimestore']['datadir']);
        }
    }

    // Backup nodered

    if (file_exists("$NRdir") && ($nodered == "Y")) {
    echo "Backing up node-red data\n";
    foreach (
    $iterator = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($NRdir, \RecursiveDirectoryIterator::SKIP_DOTS),
    \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
    if ($item->isDir()) {
    mkdir("$tdir/nodered" . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    } else {
    copy($item, "$tdir/nodered" . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }
      }
    }

    // Run bash script

    $output = exec("lib/./sync.sh");

