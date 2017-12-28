#!/bin/bash
if [ ! -d /data/mysql ]; then
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	rm -rf /var/lib/mysql
	chown -R mysql:mysql /data/mysql
	mv /var/www /data/www
	service mysql start
	# Install Initial DBs
	mysql -uroot -pSmartCAN -h localhost < /opt/init-DB/domotique.sql
	mysql -uroot -pSmartCAN -h localhost mysql < /opt/init-DB/mysql.sql
fi
chmod 0644 /etc/mysql/mariadb.conf.d/50-client.cnf

# Start MySQL Server
service mysql start
mysql -uroot -pSmartCAN -e "SHOW DATABASES";

# crontab
echo "*   * * * * php /data/www/smartcan/bin/message.php" | crontab
echo "*   * * * * php /data/www/smartcan/bin/temperatures.php" | crontab
echo "*   * * * * /etc/init.d/domocan-monitor" | crontab
echo "*   * * * * ( sleep 20 ; php /var/www/smartcan/bin/chauffage.php )" | crontab
echo "5   4 * * * php /data/www/smartcan/bin/dailysync.php" | crontab
echo "*/5 * * * *  php /data/www/smartcan/bin/fivemin-clean.php" | crontab

# Start Samba
service samba start

# Start PHP
service php7.0-fpm start

# Start NGINX
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# Start DomoCAN Server
/etc/init.d/domocan-init start

# To prevent Docker from exiting
bash
