# dropbox-archive
The aim of these scripts are to;
+ copy your emoncms feed data folders and obtain a dump of your emoncms MYSQL database
+ option to backup node-red flows, credentials and configuration settings
+ create a datestamped tar.gz compressed archive of the data
+ upload the archive to your Dropbox cloud account
+ each time the script is run, it will delete both local and cloud archives which are older that 7 days (can be changed in script)

### Installation and Setup  
`git clone https://github.com/Paul-Reed/dropbox-archive.git`  
`cd dropbox-archive && nano dropbox_filemanager.sh`  
Ensure that your emoncms database credentials are added where indicated, and if you also want to back up your node-red flows, credential and settings, select (Y)es in the configuration,  save & exit.

Run the script  
`./dropbox_filemanager.sh`  
The first time that you run the script, it will enter Dropbox setup mode, and prompt you to authorize the app with Dropbox.

This only needs to be done once, and the most common error cause is not copying the authorization URL accurately due to the wrong interpritation of numbers and letters such as O (letter) 0 (number) & 1 (number) I (letter).

Once authorized, it will continue to create a compressed datestamped backup file of your emoncms data directories & MYSQL database within your Dropbox cloud 'App' folder.

Running the script subsequently, will add further archives to Dropbox, which can be done either manually, by Cron, by node-red, or by other means.

By default, the script will retain 7 days of backups, although that can be changed in the dropbox_filemanager.sh script, archives older than 7 days are automatically deleted both locally and remotely in Dropbox.

For details of how to use the script to restore the cloud backups to your pi, see the various options listed in the [Dropbox-Uploader](Dropbox-Uploader-README.md) read-me.  

ALSO, try the Intractive Dropbox SHELL to manage your Dropbox cloud app storage. To run the script, cd to your dropbox-archive directory, and run;  
`./dropShell.sh`  
Dropshell will then list the available commands, such as 'list' all files in your Dropbox cloud app account, cd, pwd, get, put, cat, rm, mkdir, mv, cp, free, lls, lpwd, lcd.

Many thanks to Andrea Fabrizi for his brilliant Dropbox_uploader script.
