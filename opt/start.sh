#!/bin/sh

# Start Samba
service samba start

#Start mysql
service mysql start

# Start PHP
service php7.0-fpm start

# Start NGINX
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# To prevent Docker from exiting
journalctl -f
