Paydate Commandline Tool
=======

Description of the application
------------------------------

Is a code sample written by Hugo Mauricio Prado Macat  that complies as a solution for the description of "The Burroughs Test" for the recruitment process in order to demonstrate professional experience software engineering principles.

Description of the test:

Small command-line utility to help a fictional company determine the dates on which they need to pay salaries to their Sales Department.

The company handles their Sales payroll in the following way:

* Sales staff get a regular fixed base monthly salary, plus a monthly bonus 
* The base salaries are paid on the last day of the month, unless that day is a Saturday, a Sunday (weekend). In that case, salaries are paid before the weekend. For the sake of this application,  please do not take into account public holidays. 
* On the 15th of every month bonuses are paid for the previous month, unless that day is a weekend. In that case, they are paid the first Wednesday after the 15th

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

