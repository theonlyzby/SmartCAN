#!/bin/bash

# Activate can0 Interface
ifup can0

if [ ! -d /data/mysql ]; then
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	chown -R mysql:mysql /data/mysql
	mv /var/www /data/www
	mv /var/sys-files /data/sys-files
	chmod 777 /data/www/smartcan/www/conf
	service mysql start
	# Install Initial DBs
	mysql -uroot -pSmartCAN -h localhost < /opt/init-DB/domotique.sql
	mysql -uroot -pSmartCAN -h localhost mysql < /opt/init-DB/mysql.sql
	mv /data/www/smartcan/bin/resin/* /data/www/smartcan/bin
	chmod +x /data/www/smartcan/bin/server_udp
	chmod +x /data/www/smartcan/bin/domocan-bridge
	chmod +x /data/www/smartcan/bin/domocan-bridge-and-web
	chmod +x /data/www/smartcan/bin/rx-DOMOCAN3.php
	chmod 777 /data/www/smartcan/www/conf/
	mkdir /data/www/backups
	chmod 777 /data/www/backups
fi

chmod 0644 /etc/mysql/mariadb.conf.d/50-client.cnf
chmod 777 /usr/local/nginx/conf/
chmod 777 /usr/local/nginx/conf/nginx.conf
chmod 777 /data/www/smartcan/www/js/
chmod 777 /data/www/smartcan/www/js/weather.js

# Update SmartCAN files (if needed)
cp -r /var/www/smartBACKUP/* /data/www/smartcan/
if [ -f /var/www/smartBACKUP/uploads/domotique-update.sql ]; then
	mysql -uroot -pSmartCAN -h localhost domotique < /var/www/smartBACKUP/uploads/domotique-update.sql
	rm -rf /var/www/smartBACKUP/uploads/domotique-update.sql
fi
rm -rf /data/www/smartcan/generate-smartbackuptar.sh
rm -rf /data/www/smartBACKUP/*.sql
# Remove non-persistent files (mysql DB and www files)
rm -rf /var/www
rm -rf /var/lib/mysql

# Start MySQL Server
service mysql start
mysql -uroot -pSmartCAN -h localhost -e "SHOW DATABASES";

# Start Samba
service samba start

# Start PHP
service php7.0-fpm start

# Start NGINX
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# Start DomoCAN Server
# chmod +x /etc/init.d/domocan-monitor
# chmod +x /etc/init.d/domocan-init
# /etc/init.d/domocan-init start

# Restore crontab Configuration
crontab /data/sys-files/crontab

# Start cron
service cron start

# To prevent Docker from exiting
bash
