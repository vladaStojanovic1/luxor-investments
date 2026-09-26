#!/usr/bin/env bash

## Nginx
sudo apt-get -y update
sudo apt-get -y install nginx

## Mysql - MariaDB
export DEBIAN_FRONTEND=noninteractive
sudo debconf-set-selections <<< "maria-db-server mysql-server/root_password password root"
sudo debconf-set-selections <<< "maria-db-server mysql-server/root_password_again password root"
sudo apt-get -q -y install mariadb-server

## PHP
sudo add-apt-repository ppa:ondrej/php
sudo apt-get -y update
sudo apt-get -y install php7.2-fpm php7.2-mbstring php7.2-xml php7.2-curl php7.2-dev php-pear nfs-common php7.2-cli php7.2-mysql php7.2-gd php7.2-imagick php7.2-recode php7.2-tidy php7.2-xmlrpc
sudo pecl channel-update pecl.php.net
sudo pecl install xdebug

## Phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password root"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password root"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password root"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin
sudo ln -s /usr/share/phpmyadmin /vagrant
sudo phpenmod mcrypt

## Install wp-cli
curl -L https://raw.github.com/wp-cli/builds/gh-pages/phar/wp-cli.phar > wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/bin/wp

## MySql
mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS db_main"
mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON db_main.* TO 'db_user'@'localhost' IDENTIFIED BY 'db_pass'"
mysql -uroot -proot -e "FLUSH PRIVILEGES"

cat <<EOT >> /etc/php/7.2/fpm/php.ini
[xdebug]
zend_extension=/usr/lib/php/20170718/xdebug.so
xdebug.remote_enable=1
xdebug.remote_host=10.0.2.2
xdebug.remote_port=9000
EOT

sudo sed -i -r -e  's/^(upload_max_filesize).*/\1=64M/g' -e 's/^(post_max_size).*/\1=128M/g' /etc/php/7.2/fpm/php.ini

sudo service php7.2-fpm restart

cat > /etc/nginx/sites-available/default.vhost <<EOF
server {
	listen 80;
	listen [::]:80;

	root /vagrant;

	index index.php index.html index.htm index.nginx-debian.html;
	server_name $2;

	client_max_body_size 100M;

	location / {
		try_files \$uri \$uri/ /index.php?\$args;
		add_header Last-Modified \$date_gmt;
        add_header Cache-Control 'no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0';
		sendfile off;
        if_modified_since off;
        expires off;
        etag off;
        proxy_no_cache 1;
        proxy_cache_bypass 1;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_param  SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
		fastcgi_pass unix:/run/php/php7.2-fpm.sock;
	}

	location ~ /\.ht {
		deny all;
	}
}
EOF

ln -sf /etc/nginx/sites-available/default.vhost /etc/nginx/sites-enabled/default.vhost

#sudo openssl req -nodes -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -subj '/CN=localhost'

sudo service nginx restart