#!/usr/bin/env bash

DB_HOST=localhost
DB_PASSWORD=secret
DB_NAME=vagrant
DB_USER=vagrant
WEBSERVER_URL=http://192.168.33.10/ # See Vagrantfile config.vm.network

LOG_FILE=/vagrant/vm_build.log

# Clear logs
> ${LOG_FILE}

echo -e "--- Updating packages list ---"
apt-get -y -qq update

# Setting mysql root password to "root"
echo -e "--- Installing MySQL packages ---"
debconf-set-selections <<< "mysql-server mysql-server/root_password password ${DB_PASSWORD}"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${DB_PASSWORD}"

apt-get -y install mysql-server >> ${LOG_FILE} 2>&1

echo -e "--- Setting up MySQL ---"
mysql -uroot -p${DB_PASSWORD} -e "CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci" >> ${LOG_FILE} 2>&1
mysql -uroot -p${DB_PASSWORD} -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}'" >> ${LOG_FILE} 2>&1

echo -e "--- Installing PHP ---"
apt-get -y install php7.0 php7.0-mysql php7.0-cli php7.0-fpm php7.0-gd php7.0-curl php7.0-zip php7.0-xml >> ${LOG_FILE} 2>&1

echo -e "--- Installing Nginx ---"
apt-get -y install nginx >> ${LOG_FILE} 2>&1

echo -e "--- Installing MailUtils ---"
debconf-set-selections <<< "postfix postfix/mailname string localhost"
debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"
apt-get install -y mailutils >> ${LOG_FILE} 2>&1

echo -e "--- Setting up Nginx ---"
sed -i 's/www-data/ubuntu/g' /etc/php/7.0/fpm/pool.d/www.conf >> ${LOG_FILE} 2>&1
sed -i 's/www-data/ubuntu/g' /etc/nginx/nginx.conf >> ${LOG_FILE} 2>&1
sed -i 's/sendfile on/sendfile off/g' /etc/nginx/nginx.conf >> ${LOG_FILE} 2>&1
cp /vagrant/site.nginx.conf /etc/nginx/sites-available/site >> ${LOG_FILE} 2>&1
ln -s /etc/nginx/sites-available/site /etc/nginx/sites-enabled/. >> ${LOG_FILE} 2>&1
rm /etc/nginx/sites-enabled/default >> ${LOG_FILE} 2>&1
service php7.0-fpm restart >> ${LOG_FILE} 2>&1
service nginx restart >> ${LOG_FILE} 2>&1

echo -e "Installation finished. Webserver is available on ${WEBSERVER_URL}"