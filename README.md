# catalyst_assessment

## Catalyst_assessment

This is designed and developed as a solution to a company's assessment. The main purpose is to get the data from a .csv file and validate them against email addresses, and insert the filtered data into a database. It is a command line program developed using php 8.2.4. It takes necessary data from the user as command line arguments. It provides a command line log which contains steps, Success or error logs while executing the program.

## Prerequisites

php 8.2.4 and mySql need to be installed. No other external libraries or software required.

## Cli arguments

There are few cli arguments need to be provided. They are mainly two types, properties and commands.

1 Properties

Properties are mandatory to run the program smoothly.

Property : username
Key : -u
Value : username
Description : user name of the database. Key is mandatory and the value is also mandatory. If key or
value missing then program will give an error message.
Eg: -uroot

Property : password
Key : -p
Value : password or keep it as blank
Description : password of the database. Key is mandatory but the value is optional. If key is missing then program will give an error message. Can use only key without any value.
Eg: -proot or -p

Property : host
Key : -h
Value : host
Description : host of the database server. Key is mandatory and the value is also mandatory. If key or value is missing then program will give an error message.
Eg: -hlocalhost

Property : file
Key : --file
Value : file
Description : user name for the database. Key -u is mandatory and the value is also mandatory. If key or value is missing then program will give an error message.
Eg: --file=users.csv

2 Execution commands

Command : --create_table
Description : Run the script up to creating a table. To execute this command, user need to give all the required properties as arguments (-u -p -h --file) otherwise it will give an error message.
Eg: php user_upload.php -uroot -p -hlocalhost --file=users.csv --create_table

Command : --dry_run
Description : Run the entire script but the data is not inserted in to the table. Valid data is shown as a log and error is shown for the invalid data. To execute this command, user need to give all the required properties as arguments (-u -p -h --file) otherwise it will give an error message.
Eg: php user_upload.php -uroot -p -hlocalhost --file=users.csv --dry_run

Command : Not specify a command
Description : Run the entire script and insert filtered data in to the table. Error is shown for the invalid data. To execute this command, user need to give all the required properties as arguments (-u -p -h --file) otherwise it will give an error message.
Eg: php user_upload.php -uroot -p -hlocalhost --file=users.csv

Command : --help
Description : Display the help
Eg: php user_upload.php --help

## Assumptions

- The file which is given as property (--file=users.csv) need to be int the same directory.
