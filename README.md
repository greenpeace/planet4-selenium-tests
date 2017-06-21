# Selenium and PHP testsuite

The purpose of this project is to have [Selenium][] running to test greenpeace.org sites as part of the continuous integration of Planet4 development.

### Requisites
This repository is verified to be run in UNIX operating systems with the following characteristics

    PHP            5.3.29
    Firefox        47.0.1
    Selenium       2.53.1


Step by step build
===========================================

##  GET THE CODE

### Github

    git clone git@github.com:greenpeace/planet4-selenium-tests.git

##  SELENIUM

*   Download the selenium-server-standalone-2.53.1.jar file provided here:

    http://selenium-release.storage.googleapis.com/index.html

*   Download and run that file

        java -jar selenium-server-standalone-#.jar

*   You can also run it on a remote host, you will need to set it up as a grid/node by running two instances

        java -jar selenium-server-standalone-2.53.1.jar -role hub
        java -jar selenium-server-standalone-2.53.1.jar -role node

*   Then when you create a session, be sure to pass the url to where your server is running.

    // This would be the url of the host running the server-standalone.jar
       $host = 'http://localhost:4444/wd/hub'; // this is the default

*   Make sure you have firefox installed on your selenium host!


## PHPUNIT 

*   You can download PHPunit using your local package manager (apt-get, yum, brew, etc), for example

        apt-get phpunit

*   If you don't want to use your local package manager you can download the composer.phar

        curl -sS https://getcomposer.org/installer | php

*   Install the library.

        php composer.phar install

*   To run unit tests then simply run:

        vendor/bin/phpunit tests/sample/AN_changedomain.php;

* A bash script has been created to run all tests and can be run as follows (currently only runs 1 test)

        $ ./run_sampletest.sh


About Facebook php-webdriver
===========================================

This WebDriver client is a driver developped by Facebook. It aims to be as close as possible to bindings in other languages.
The concepts are very similar to the Java, .NET, Python and Ruby bindings for WebDriver.

Looking for documentation about php-webdriver?
- [API](http://facebook.github.io/php-webdriver/)
- [Repository](https://github.com/facebook/php-webdriver)


##  Useful links

Selenium [docs and wiki](http://docs.seleniumhq.org/docs/ and https://code.google.com/p/selenium/wiki)
Integration with PHPUnit [Blogpost](http://codeception.com/11-12-2013/working-with-phpunit-and-selenium-webdriver.html) | [Demo Project](https://github.com/DavertMik/php-webdriver-demo)



# Coming soon...
* groups tagging to include/exclude features to be tested
* Chrome browser support
* mp4 video recording
