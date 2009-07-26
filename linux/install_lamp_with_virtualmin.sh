#!/bin/bash

# The following scripts is meant to be run on a freshly installed server.
# It uses the virtualmin control panel to install all of the LAMP Tools and Services (http://www.virtualmin.com/download)
# It will even configure hostname if it's not set.
# Once this script is complete, login using http://hostname:10000 with root credentials.
# 1. Ensure this in your virtualhost directives which can be configured in System Settings > Default Settings > Server Templates > Apache Website > Directives and settings for new websites
# Options -Indexes IncludesNOEXEC FollowSymLinks ExecCGI
# AddHandler fcgid-script .php
# FCGIWrapper ${HOME}/fcgi-bin/php5.fcgi .php
# 2. Ensure this is set in your php.ini file which can be configured in /etc/php5/cgi/php.ini
# cgi.fix_pathinfo = 0; 

mkdir ~/src
cd ~/src
wget http://software.virtualmin.com/gpl/scripts/install.sh
/bin/sh install.sh
apt-get install php5-mysql
mkdir /etc/skel/fcgi-bin

echo "#!/bin/bash
PHPRC=\$PWD/fcgi-bin
export PHPRC
umask 022
SCRIPT_FILENAME=\$PATH_TRANSLATED
export SCRIPT_FILENAME
exec /usr/bin/php-cgi" > /etc/skel/fcgi-bin/php5.fcgi

chmod +x /etc/skel/fcgi-bin/php5.fcgi
cp /etc/php5/cgi/php.ini /etc/skel/fcgi-bin/php.ini