# catalyst_assessment

create_table -> Run the script up to creating a table
php user_upload.php -uroot -p -hlocalhost --file=users.csv --create_table

dry_run -> Run the entire script but data insertion to table is not happening
php user_upload.php -uroot -p -hlocalhost --file=users.csv --dry_run

no command -> Run the entire script and data insertion to table is happening
php user_upload.php -uroot -p -hlocalhost --file=users.csv

help -> Display help
php user_upload.php -uroot -p -hlocalhost --file=users.csv
