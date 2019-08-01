GRANT ALL PRIVILEGES ON ychoice.* TO 'ychoice'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

###settings to log slow queries

SET GLOBAL slow_query_log = 'ON';
SET GLOBAL slow_query_log_file = '/var/log/mysql/localhost-slow.log';
SET GLOBAL log_queries_not_using_indexes = 'ON';
SET SESSION long_query_time = 1;
SET SESSION min_examined_row_limit = 100;


