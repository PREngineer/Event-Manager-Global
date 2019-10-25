#!/bin/bash
# Script Name: Event-Manager installer
# Author: PREngineer (Jorge Pabon) - pianistapr@hotmail.com
# Publisher: Jorge Pabon
# License: Personal Use (1 device) per payment
###########################################################

#-----------------------------------------------------------------------------

progress-bar() {
  local duration=${1}


    already_done() { for ((done=0; done<$elapsed; done++)); do printf "▇"; done }
    remaining() { for ((remain=$elapsed; remain<$duration; remain++)); do printf " "; done }
    percentage() { printf "| %s%%" $(( (($elapsed)*100)/($duration)*100/100 )); }
    clean_line() { printf "\r"; }

  for (( elapsed=1; elapsed<=$duration; elapsed++ )); do
      already_done; remaining; percentage
      sleep 1
      clean_line
  done
  clean_line
}

#-----------------------------------------------------------------------------

# Color definition variables
YELLOW='\e[33;3m'
RED='\e[91m'
BLACK='\033[0m'
CYAN='\e[96m'
GREEN='\e[92m'

# Display the Title Information
echo
echo -e $RED
echo -e "╔═════════════════════════════════════════════════════════════════════════════════════════════════════════════╗"
echo -e "║███████╗██╗   ██╗███████╗███╗   ██╗████████╗   ███╗   ███╗ █████╗ ███╗   ██╗ █████╗  ██████╗ ███████╗██████╗ ║"
echo -e "║██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝   ████╗ ████║██╔══██╗████╗  ██║██╔══██╗██╔════╝ ██╔════╝██╔══██╗║"
echo -e "║█████╗  ██║   ██║█████╗  ██╔██╗ ██║   ██║█████╗██╔████╔██║███████║██╔██╗ ██║███████║██║  ███╗█████╗  ██████╔╝║"
echo -e "║██╔══╝  ╚██╗ ██╔╝██╔══╝  ██║╚██╗██║   ██║╚════╝██║╚██╔╝██║██╔══██║██║╚██╗██║██╔══██║██║   ██║██╔══╝  ██╔══██╗║"
echo -e "║███████╗ ╚████╔╝ ███████╗██║ ╚████║   ██║      ██║ ╚═╝ ██║██║  ██║██║ ╚████║██║  ██║╚██████╔╝███████╗██║  ██║║"
echo -e "║╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝      ╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝║"
echo -e "╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════╝"
echo -e $CYAN"          ╔Brought to you by PREngineer (Jorge Pabón)╗     "
echo -e $CYAN"          ╚════https://www.github.com/PREngineer═════╝     "
echo
echo -e $GREEN'Installer'
echo
echo -e $RED'This installation script is intended for Ubuntu 18.04.'
echo -e 'It has not been tested on any other version of Ubuntu.'

echo
echo -e $YELLOW"|--> Updating Ubuntu..."$BLACK
echo
echo

sudo apt-get -y update #&>/dev/null & progress-bar 10

echo
echo
echo -e $YELLOW"|--> Upgrading Ubuntu packages..."$BLACK
echo
echo

sudo apt-get -y upgrade #&>/dev/null & progress-bar 10

echo
echo
echo -e $YELLOW"|--> Installing Apache 2 Web Server..."$BLACK
echo
echo

sudo apt-get -y install apache2 #&>/dev/null & progress-bar 5

echo
echo
echo -e $YELLOW"|--> Opening Firewall for Apache 2 Web Server..."$BLACK
echo
echo

sudo ufw allow in “Apache Full” #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Installing MySQL Server 5.7..."$BLACK
echo
echo

sudo apt-get -y install mysql-server #&>/dev/null & progress-bar 5

echo
echo
echo -e $YELLOW"|--> Installing PHP 7.2..."$BLACK
echo
echo

sudo apt-get -y install php7.2 libapache2-mod-php7.2 php7.2-mysql php-memcache php7.2-mbstring php7.2-opcache php-apcu #&>/dev/null & progress-bar 60

echo
echo
echo -e $YELLOW"|--> Installing Unzip..."$BLACK
echo
echo

sudo apt-get -y install unzip #&>/dev/null & progress-bar 5

echo
echo
echo -e $YELLOW"|--> Downloading PHPMyAdmin 4.8.0.1..."$BLACK
echo
echo

cd /var/www/html
sudo wget https://files.phpmyadmin.net/phpMyAdmin/4.8.0.1/phpMyAdmin-4.8.0.1-all-languages.zip #&>/dev/null & progress-bar 5

echo
echo
echo -e $YELLOW"|--> Unzipping PHPMyAdmin 4.8.0.1..."$BLACK
echo
echo

sudo unzip phpMyAdmin-4.8.0.1-all-languages.zip #&>/dev/null & progress-bar 5

echo
echo
echo -e $YELLOW"|--> Renaming PHPMyAdmin 4.8.0.1 download..."$BLACK
echo
echo

mv phpMyAdmin-4.8.0.1-all-languages phpmyadmin #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Removing previous version of PHPMyAdmin 4.8.0.1..."$BLACK
echo
echo

sudo rm -R /usr/share/phpmyadmin #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Replacing with PHPMyAdmin 4.8.0.1..."$BLACK
echo
echo

mv phpmyadmin /usr/share/ #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Deleting downloaded PHPMyAdmin 4.8.0.1 zip file..."$BLACK
echo
echo

sudo rm phpMyAdmin-4.8.0.1-all-languages.zip #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Installing Git..."$BLACK
echo
echo

sudo apt-get -y install git #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Redirecting root to application..."$BLACK
echo
echo

echo '
<!DOCTYPE html>
<html lang="en">

  <head>
    <script type="text/javascript">
        window.location.href = "Event-Manager/content/index.php"
    </script>
  </head>

  <body>
    You should be redirected to the /content/index.php page.<br>
    If you are not, please click <a href="Event-Manager/content/index.php">here</a>
  </body>

</html>
' > /var/www/html/index.html
progress-bar 2

echo
echo
echo -e $YELLOW"|--> Getting latest version of application..."$BLACK
echo
echo

sudo git clone https://www.github.com/PREngineer/Event-Manager #&>/dev/null & progress-bar 10

echo
echo
echo -e $YELLOW"|--> Fixing file permissions..."$BLACK
echo
echo

sudo chmod -R 777 /var/www/html #&>/dev/null & progress-bar 2

echo
echo
echo -e $YELLOW"|--> Making sure that the settings file is not present..."$BLACK
echo
echo

sudo rm /var/www/html/Event-Manager/functions/settings.php #&>/dev/null & progress-bar 2

echo
echo
echo -e        "********************************************************"
echo -e $YELLOW"* Things that you might have to do later:              *"
echo -e        "********************************************************"
echo -e        "* 1. Secure your mysql installation:                   *"
echo -e        "*    sudo mysql_secure_installation                    *"
echo -e        "* Carefully read the questions to select your level of *"
echo -e        "* comfort with the security.                           *"$RED
echo -e        "* Be aware that you might lose root access on          *"
echo -e        "* localhost.                                           *"$YELLOW
echo -e        "********************************************************"
echo -e        "* 2. Fix root permissions:                             *"
echo -e        "*    sudo mysql                                        *"
echo -e        "*    ALTER USER 'root'@'localhost' IDENTIFIED WITH     *"
echo -e        "*      mysql_native_password BY ‘password’;            *"
echo -e        "*    GRANT ALL PRIVILEGES ON *.* TO ‘root’@‘%’;        *"
echo -e        "********************************************************"
echo -e        "* 3. Your PHPMyAdmin default credentials should be     *"
echo -e        "*    Username: phpmyadmin                              *"
echo -e        "*    Password: password                                *"
echo -e        "********************************************************"
echo -e        "* 4. You should be able to view the setup page in the  *"
echo -e        "* following URL:                                       *"
echo -e        "* http://localhost/ or http://YOURIP/                  *"
echo -e        "********************************************************"
echo
echo

exit 0
