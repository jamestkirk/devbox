#!/bin/sh
set -e

# This script will compile the ssh2 extension for php (http://us3.php.net/manual/en/book.ssh2.php).
# Once installed, you'll be able to use these functions (http://us3.php.net/manual/en/ref.ssh2.php).
#
# Additional Resources Below
# - PECL SSH2 - http://pecl.php.net/package/ssh2
# - Installing “Secure Shell2 (SSH2)” PHP Extension - http://cubiclegeneration.com/web-servers/installing-secure-shell2-ssh2-php-extension


== Installing SSH2 ==
'''Please Note:''' The installation of SSH2 '''requires''' [http://wiki.dreamhost.com/Installing_PHP5 Installing PHP5]. If you are unable to do so, then you may not be able to use SSH2.

Below is the install script for the SSH2 PHP module.  I'm not a bourne/bash scripter so I am aware the code below is shit.


#### User Configuration Options
# Temporary src directory - WARNING: This will be wiped clean.
SRCDIR=${HOME}/src

# Note: This *MUST* be set to your PHP5 installation directory!
PHPDIR=${HOME}/php5

PREFIXDIR=${HOME}/prefixdir

## Program Version Configuration
AUTOCONF="autoconf-2.61"

# Don't touch unless you know what you're doing!
LIBSSH2="libssh2-1.1"
LIBSSH2FEATURES="--prefix=${PREFIXDIR}"

SSH2="ssh2-0.11.0"
SSH2FEATURES="--prefix=${PREFIXDIR} --with-php-config=${PHPDIR}/bin/php-config --with-ssh2=${PREFIXDIR}"

#### END User Configuration Options

########## DO NOT MODIFY BELOW ##########
sleep 1s

# Push the install dir's bin directory into the path
export PATH=${PREFIXDIR}/bin:$PATH
export PHP_PREFIX=${PHPDIR}/bin

# Clear and/or create the src directory.
if [ -d ${SRCDIR} ]; then
		  echo "src directory already exists! Cleaning it..."
		  rm -rf $SRCDIR/*
else
		  echo "Creating src directory..."
		  mkdir -p ${SRCDIR}
fi

## Check if packages already exist and get packages the ones that don't.
cd ${SRCDIR}

# Wget options
WGETOPT="-t1 -T10 -w5 -q -c"

# Do some of our own error checking here too.
if [ -a ${SRCDIR}/${LIBSSH2}.tar.gz ]; then
		  echo "Skipping wget of ${LIBSSH2}.tar.gz"
else
		  wget $WGETOPT http://softlayer.dl.sourceforge.net/sourceforge/libssh2/${LIBSSH2}.tar.gz

		  if [ -a ${SRCDIR}/${LIBSSH2}.tar.gz ]; then
			echo "Got ${LIBSSH2}.tar.gz"
		  else
				echo "Failed to get ${LIBSSH2}.tar.gz. Aborting install!"
				exit 0
		  fi
fi

if [ -a ${SRCDIR}/${AUTOCONF}.tar.gz ]; then
		  echo "Skipping wget of ${AUTOCONF}.tar.gz"
else
		  wget $WGETOPT ftp://ftp.ucsb.edu/pub/mirrors/linux/gentoo/distfiles/${AUTOCONF}.tar.gz
		  # If primary mirror fails, use the alternative mirror.
		  if [ -a ${SRCDIR}/${AUTOCONF}.tar.gz ]; then
			echo "Got ${AUTOCONF}.tar.gz"
		  else
				wget $WGETOPT ftp://ftp.gnu.org/gnu/autoconf/${AUTOCONF}.tar.gz
				# Check to make sure the alternative mirror worked.
				if [ -a ${SRCDIR}/${AUTOCONF}.tar.gz ]; then
					echo "Got ${AUTOCONF}.tar.gz"
				else
					echo "Failed to get ${AUTOCONF}.tar.gz. Aborting install!"
					exit 0
				fi
		  fi
fi

if [ -a ${SRCDIR}/${SSH2}.tgz ]; then
		  echo "Skipping wget of ${SSH2}.tgz"
else
		  wget $WGETOPT http://pecl.php.net/get/${SSH2}.tgz
		  # If primary mirror fails, use the alternative mirror.
		  if [ -a ${SRCDIR}/${SSH2}.tgz ]; then
			echo "Got ${SSH2}.tgz"
		  else
				echo "Failed to get ${SSH2}.tgz. Aborting install!"
				exit 0
		  fi
fi

# Extract the src files into the src directory.
cd ${SRCDIR}
echo "Extracting ${LIBSSH2}..."
tar xzf ${SRCDIR}/${LIBSSH2}.tar.gz > /dev/null
echo "Done."

cd ${SRCDIR}
echo "Extracting ${AUTOCONF}..."
tar xzf ${SRCDIR}/${AUTOCONF}.tar.gz > /dev/null

echo "Extracting ${SSH2}..."
tar xzf ${SRCDIR}/${SSH2}.tgz > /dev/null
echo "Done."


## Compile

#AUTOCONF
cd ${SRCDIR}/${AUTOCONF}
./configure --prefix=${PREFIXDIR}
make install

#LIBSSH2
cd ${SRCDIR}/${LIBSSH2}
./configure ${LIBSSH2FEATURES}
make all install


#SSH2
cd ${SRCDIR}/${SSH2}
$PHP_PREFIX/phpize
./configure ${SSH2FEATURES}
make

# Post install clean-up.
sleep 2s
cd ${SRCDIR} && clear

## End of Install
echo "Installation completed! Your php extension should be in ${SSH2}/modules" `date +%r`

#EOF