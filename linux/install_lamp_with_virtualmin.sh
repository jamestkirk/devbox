#!/bin/bash

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

################ Script Done, Please do the following manually. ####################
# 1. Ensure this in your virtualhost directives
# Options -Indexes IncludesNOEXEC FollowSymLinks ExecCGI
# AddHandler fcgid-script .php
# FCGIWrapper ${HOME}/fcgi-bin/php5.fcgi .php

# 2. Ensure this is set in your php.ini file
# cgi.fix_pathinfo = 0;
