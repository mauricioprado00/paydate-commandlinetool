Paydate Commandline Tool
=======

![alt tag](https://raw.github.com/mauricioprado00/the-burroughs-test/master/diagram.png)


Installation / run
------------------
since it's based on composer you need to do the following:

    composer install

after that you are all set to run the application.


    ./bin/paydate


will provide the help screen. Some of the examples of usage are:

Calculate next 12 months paydates:

**paydate calculate**    generates next 12 months paydates from not to the console output                                                                                               

Calculate next 12 months paydates to file:

**paydate calculate --to-file="/tmp/next_paydates.csv"**    generates next 12 months paydates from not to the console output                                                            

Calculate next 24 months paydates:

**paydate calculate next 24** generates next 24 months paydates from now to the console output                                                                                       

Calculate next 24 months paydates from May of 2015:

**paydate calculate next 24 from 201505** generates next 24 months paydates from May 2015 to the console output    


Test cases run
---------------
to run the test cases you can do it either with ant 

    ant tests

or by specifying the directory:

   ./bin/composer/phpunit tests
