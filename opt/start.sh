#!/bin/bash
if [ ! -d /data/mysql ]; then
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	rm -rf /var/lib/mysql
	chown -R mysql:mysql /data/mysql
fi
service mysql start
mysql -uroot -pSmartCAN -e "SHOW DATABASES";


# Start Samba
service samba start

# Start PHP
service php7.0-fpm start

# Start NGINX
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# To prevent Docker from exiting
bash
