# php-web-db-app
Simple PHP application to demonstrate NSX micro segmentation between WEB and DB. 

## Installation instructions 
You need web server (for example apache) with PHP and MySQL database.

You have to create a MySQL Database and grant a MySQL User the access. Simple example is shown below. Login to MySQL as a root
```
mysql -u root -p
```
and run following DB script ...

```
mysql -u root -p
CREATE DATABASE vmware;
CREATE USER vmware@localhost IDENTIFIED BY 'vmware';
# allow access from anywehere – NSX DFW will restrict access to MySQL
GRANT ALL PRIVILEGES ON vmware.* TO vmware IDENTIFIED BY 'vmware' WITH GRANT OPTION;
FLUSH PRIVILEGES;
show grants;
CREATE TABLE access_log (
 ACCESS_TIME DATETIME,
 ACCESS_TO VARCHAR(250),
 REMOTE_ADDR VARCHAR(250),
 HTTP_X_FORWARDED_FOR VARCHAR(250),
 HTTP_CLIENT_IP VARCHAR(250),
 HTTP_X_FORWARDED VARCHAR(250),
 HTTP_FORWARDED_FOR VARCHAR(250),
 HTTP_FORWARDED VARCHAR(250),
 HTTP_X_CLUSTER_CLIENT_IP VARCHAR(250)
);
exit
```

