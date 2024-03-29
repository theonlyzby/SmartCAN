#!/bin/bash
now=$(date +"%Y-%m-%d %H:%M:%S")
echo "       +----------------------------------------+"
echo "       |   Restarting at " "$now" "   |"
echo "       +----------------------------------------+"

# Activate can0 Interface
#ifup can0
first_boot=0

#if [ ! -d /data/mysql ]; then
if [ ! -f /data/Initial-Install.txt ]; then
	first_boot=1
	touch /data/Initial-Install.txt
	date +"%Y-%m-%d %H:%M:%S" >> /data/Initial-Install.txt
    echo "#######################################################"
    echo "## 0. First Boot => Move files to Persistent Storage ##"
    echo "#######################################################"

	# Moving mySQL Database
	mkdir -p /data/mysql
	cp -r /var/lib/mysql/* /data/mysql
	#sed -i -e "s@^datadir.*@datadir = /data/mysql@" /etc/mysql/my.cnf
	chown -R mysql:mysql /data/mysql
	# Moving web pages into /data/www (Persistent storage)
	cp -r /var/www /data
	rm -r /var/www
	# Moving files into /data/sys-files (Persistent storage)
	cp -r /var/sys-files /data
	rm -r /var/sys-files
	chmod 777 /data/www/smartcan/www/conf
	service mysql start
	# Install Initial DBs
	mysql -uroot -pSmartCAN -h localhost < /opt/init-DB/domotique.sql
	mysql -uroot -pSmartCAN -h localhost mysql < /opt/init-DB/mysql.sql
	# Modifying access properties for some files 
	chmod +x /data/www/smartcan/bin/server_udp
	chmod +x /data/www/smartcan/bin/domocan-bridge
	chmod +x /data/www/smartcan/bin/domocan-bridge-and-web
	chmod +x /data/www/smartcan/bin/rx-DOMOCAN3.php
	chmod 777 /data/www/smartcan/www/conf/*
	chmod 777 /data/www/smartcan/www/conf/config.php
	chmod 777 /data/www/smartcan/www/test.txt
	chmod 777 /data/www/log/SmartCAN-WebAdmin.log
	mkdir /data/www/backups
	chmod 777 /data/www/backups
	chmod +x  /data/www/smartBACKUP/generate-smartbackuptar.sh
	chmod 777 /data/www/smartcan/uploads
	chmod 777 /data/www/smartcan/uploads/
	chmod 777 /data/www/smartcan/webhook/*.txt
	# Copying NGINX config file
	cp /usr/local/nginx/conf/nginx.conf /data/sys-files/nginx.conf
	# Creating Mosquitot password file for user MQTTuser
	touch /data/sys-files/mosquitto/pwfile
	mosquitto_passwd -b /data/sys-files/mosquitto/pwfile MQTTuser SmartCAN
	# Cleaning node-red Install
	rm /opt/Install-NodeRed.sh
	cp -r /root/.node-red/node_modules/* /data/sys-files/node-red/node_modules
	# Finishing Zigbee2mqtt install
	cd /data/sys-files/zigbee2mqtt
	npm clean-install
	chmod +x /data/sys-files/zigbee2mqtt/update-dev-branch.sh
	# Updateing Rpi4 EEPROM
	if [[ "${Rpi4UpdateAtFirstBoot,,}" == *"y"* ]]; then
	  vcgencmd version
      vcgencmd bootloader_version
      cp -rf /opt/rpi-eeprom/firmware/ /usr/bin/
      rpi-eeprom-update -a
	fi
	echo "###############################################"
    echo "## DONE with First Boot startup process (0)! ##"
    echo "###############################################"
fi

# Setting Hostname
curl -sL -X PATCH --header "Content-Type:application/json" \
    --data '{"network": {"hostname": "'$SET_HOSTNAME'"}}' \
    "$BALENA_SUPERVISOR_ADDRESS/v1/device/host-config?apikey=$BALENA_SUPERVISOR_API_KEY"
	
# Define localhost into Hosts
echo "127.0.0.1 $HOSTNAME" >> /etc/hosts

# Set time to correct Timezone
# Default to UTC if no TIMEZONE env variable is set
echo "Setting time zone to ${TIMEZONE=UTC}"
# This only works on Debian-based images
echo "${TIMEZONE}" > /etc/timezone
dpkg-reconfigure tzdata

# Generate Env Variable => Container Start Date & Time
export CONTAINER_START=$( stat /proc/1/cmdline | grep Modify | awk '{print $2 " " $3}' )

# Insert Hardware Info towards GPIO
#rm /etc/wiringpi/cpuinfo
mkdir /etc/wiringpi
touch /etc/wiringpi/cpuinfo
echo "Hardware        : ${WiringPiHardware}" >> /etc/wiringpi/cpuinfo
echo "Revision        : ${WiringPiRevision}" >> /etc/wiringpi/cpuinfo
echo "" >> /etc/wiringpi/cpuinfo

# Update Restart Script
#rm /data/sys-files/balena-restart.sh
touch /data/sys-files/balena-restart.sh
echo "#! /bin/bash" >> /data/sys-files/balena-restart.sh
echo "curl -X POST --header "'"'"Content-Type:application/json"'"'" \
--data '{"'"'"appId"'"'": '${BALENA_APP_ID}'}' \
"'"'"${BALENA_SUPERVISOR_ADDRESS}/v1/restart?apikey=${BALENA_SUPERVISOR_API_KEY}"'"'"" \
>> /data/sys-files/balena-restart.sh
chmod u+x /data/sys-files/balena-restart.sh

rm -r /opt/init-DB
chmod 0644 /etc/mysql/mariadb.conf.d/50-client.cnf
chmod 777 /usr/local/nginx/conf/
chmod 777 /usr/local/nginx/conf/nginx.conf
chmod 777 /data/www/smartcan/www/images/plans
chmod 777 /data/www/smartcan/www/js/
chmod 777 /data/www/smartcan/www/js/weather.js
chmod 777 /data/www/smartcan/www/html/nest/nest.php

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
#mysql -uroot -pSmartCAN -h localhost -e "SHOW DATABASES";

# Start Samba
service smbd start

# Start PHP
#service php7.3-fpm start
/etc/init.d/php7.3-fpm start

# Start NGINX
cp /data/sys-files/nginx.conf /usr/local/nginx/conf/nginx.conf
/usr/local/nginx/sbin/nginx -c  /usr/local/nginx/conf/nginx.conf

# Start DomoCAN Server
ip link set can0 up type can bitrate $CANspeed
t1=$(ifconfig -a | grep -o can0)
t2='can0'
if [ "$t1" = "$t2" ]; then
  chmod +x /etc/init.d/domocan-monitor
  chmod +x /etc/init.d/domocan-init
  /etc/init.d/domocan-init start
fi

# Start Mosquitto
service mosquitto start

# Restore crontab Configuration
crontab /data/sys-files/crontab

# Start cron
service cron start

# Start Dump1090 Server (Airplane Radar)
if [[ "${Dump1090,,}" == *"y"* ]]; then
  cd /srv/dump1090
  ./dump1090 --no-fix --net --quiet --net-http-port 90 --lat ${LAT} --lon ${LONG} --gain ${GAIN} &
  # --interactive --net --net-ro-size 500 --net-ro-rate 5 --net-heartbeat 60 
fi

#chmod 0440 /etc/sudoers
# Starting SafeShutdown Script
if [[ "${NesPiCaseSoftShutdown,,}" == *"y"* ]]; then
  sudo -E python3 /opt/SafeShutdown.py &
fi

# Start Node-red
node-red-pi --settings /data/sys-files/node-red/config/settings.js &


#exec env DBUS_SYSTEM_BUS_ADDRESS=unix:path=/run/dbus/system_bus_socket /sbin/init quiet systemd.show_status=0

# Start Zigbee2MQTT
if [[ "${MQTT2Zigbee,,}" == *"y"* ]]; then
  cd /data/sys-files/zigbee2mqtt
#  npm clean-install
  npm start &
fi

bash