# dropbox-archive
**Description**  
The aims of this module are to;  
+ copy your emoncms feed data and obtain a dump of your emoncms MYSQL database
+ option to backup node-red flows, credentials and configuration settings
+ create a datestamped tar.gz compressed archive of the data
+ upload the archive to your Dropbox cloud account
+ each time the script is run, it will delete both local and cloud archives which are older that 7 days (can be changed in script)

### Installation and Setup  
Unlike a dedicated emoncms app, this module can be installed anywhere on your pi, and not necessarily within your emoncms directory.  
1) Install the module via git  
`git clone https://github.com/Paul-Reed/dropbox-archive.git`  
2) Make a copy of default.settings.conf and call it settings.conf  
`cd dropbox-archive && cp default.settings.conf settings.conf`  
3) Add your emoncms **Write** API key, your MYSQL database details, and other options to settings.conf  
`nano settings.conf`  
*Note* - ensure that the $emoncms-server setting contains your 'private' (local) IP address, as the module will use the emoncms API to replicate your data directories.  

### Run the script  
`sudo php backup.php`  
The first time that you run the script, it will prompt you to setup your Dropbox API, just follow the onscreen prompts.  
*- The most common error cause is not copying the authorization URL accurately due to the wrong interpritation of numbers and letters such as O (letter) 0 (number) & 1 (number) I (letter).*  
This only needs to be done once, and when completed, run the backup.php script again, and it will create an archive and upload it to dropbox in your Dropbox 'app' folder.

Running the script subsequently, will add further archives to Dropbox, which can be done either manually, by Cron, by node-red, or by other means.

By default, the script will retain 7 days of backups, although that can be changed in the settings.conf file, archives older than that criteria are automatically deleted both locally and remotely in Dropbox when the script in run again.

*Many thanks to Andrea Fabrizi for his brilliant Dropbox_uploader script.*
