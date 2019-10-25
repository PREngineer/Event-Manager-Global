# Please Support This Project!

This project is being developed during my spare time.  I would appreciate a donation if you found it useful.

[![](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=53CD2WNX3698E&lc=US&item_name=PREngineer&item_number=Event%2dManager&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

You can also support me by sending a BitCoin donation to the following address:

19JXFGfRUV4NedS5tBGfJhkfRrN2EQtxVo

# Events Manager

This platform is used to create, manage, and track events and assistance.

# About

This project is designed as a Progressive Web App.  This means that the project is compatible with most computer web browsers, tablets, and mobile devices while not requiring licensing with Google or Apple to be deployed through their stores.

It consists of a Web Server component and a Database component.  The Web Server component is used to display a user interface and reporting.  The Database component is used to hold the data of interest.

Admin authentication is done through an Azure Active Directory.

Core web development technologies include:
  * HTML
  * CSS
  * JavaScript
  * PHP 7

Libraries/Frameworks used:
  * Bootstrap v.3.3.7 [Stable]
    * Bootstrap coding resources:

      http://getbootstrap.com/docs/3.3/components/#input-groups

      https://www.w3schools.com/Bootstrap/default.asp

  * jQuery-3

# How to Install (Ubuntu Server)

  * Step 1 - Create /var/www/html folder and set permissions

    sudo mkdir /var

    sudo mkdir /var/www

    sudo mkdir /var/www/html

    sudo chmod -R 777 /var/www/html

  * Step 2 - Clone the repository

    cd /var/www/html

    sudo git clone https://github.com/PREngineer/Event-Manager.git
    
    cd Event-Manager

  * Step 3 - Run the installer

    sudo ./install.sh

# Important Considerations:

  * Not all links work like they do normally!

    The links' "href" property has been removed and replaced by a "link" property that is read by
    the JavaScript link handler function.

    This is done because we do not want a complete reload of a new page.  We just care about
    replacing the content of a DIV that represents the content of the pages.  We want to maintain the same navigation menu and footer.

    This also allows for better management of code in different locations without having a
    negative effect on others.

# License

All rights are reserved by Jorge Pabon.  Use of this application without a license is not authorized.  For licensing costs contact Jorge Pabon at pianistapr@hotmail.com.
