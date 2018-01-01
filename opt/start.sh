#!/bin/bash

# Activate can0 Interface
ifup can0

if [ ! -d /data/mysql ]; then
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	chown -R mysql:mysql /data/mysql
	mv /var/www /data/www
	chmod 777 /data/www/smartcan/www/conf
	service mysql start
	# Install Initial DBs
	mysql -uroot -pSmartCAN -h localhost < /opt/init-DB/domotique.sql
	mysql -uroot -pSmartCAN -h localhost mysql < /opt/init-DB/mysql.sql
	chmod +x /data/www/smartcan/bin/server_udp
	chmod +x /data/www/smartcan/bin/domocan-bridge
	chmod +x /data/www/smartcan/bin/domocan-bridge-and-web
fi
chmod 0644 /etc/mysql/mariadb.conf.d/50-client.cnf

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
/etc/init.d/domocan-init start

# Start cron
service cron start

# To prevent Docker from exiting
bash
