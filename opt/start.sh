#!/bin/bash
if [ ! -d /data/mysql ]; then
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	chown -R mysql:mysql /data/mysql
	mv /var/www /data/www
	service mysql start
	# Install Initial DBs
	mysql -uroot -pSmartCAN -h localhost < /opt/init-DB/domotique.sql
	mysql -uroot -pSmartCAN -h localhost mysql < /opt/init-DB/mysql.sql
fi
chmod 0644 /etc/mysql/mariadb.conf.d/50-client.cnf

# Remove non-persistent files (mysql DB and www files)
rm -rf /var/www
rm -rf /var/lib/mysql

# Start MySQL Server
service mysql start
mysql -uroot -pSmartCAN -e "SHOW DATABASES";

# Start Samba
service samba start

# Start PHP
service php7.0-fpm start

# Start NGINX
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# Start DomoCAN Server
/etc/init.d/domocan-init start

# Start cron
service cron start

# To prevent Docker from exiting
bash
