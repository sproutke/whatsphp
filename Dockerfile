FROM ubuntu:14.04
MAINTAINER Ongair


# Updates apt repository
RUN apt-get update -y

# Installs PHP5.6, some extensions and apcu.
RUN apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php5-5.6
RUN add-apt-repository ppa:mc3man/trusty-media
RUN apt-get update -y

RUN apt-get install -y vim telnet sudo
RUN apt-get install -y php5 --force-yes

# Installs curl, pear, wget, git, memcached and mysql-server
RUN apt-get install -y curl php-pear wget git memcached

# Installs Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Fetches a sample php.ini file with most configurations already good-to-go.
RUN wget https://raw.githubusercontent.com/naroga/docker-php56/master/php.ini
RUN rm -r /etc/php5/cli/php.ini
RUN cp php.ini /etc/php5/cli/php.ini

# Whatsapp dependencies
ADD ./install.sh /tmp/install.sh
RUN chmod +x /tmp/install.sh
RUN /tmp/install.sh
RUN apt-get install -y ffmpeg
RUN apt-get install -y php5-gd
RUN apt-get install -y php5-curl
RUN apt-get install -y libapache2-mod-php5  #php5-sockets
RUN apt-get install -y php5-sqlite
RUN apt-get install -y php5-mysql
RUN apt-get install -y php5-mcrypt
RUN php5enmod mcrypt

RUN mkdir /whatsapp
RUN cd /whatsapp && composer require ongair/whatsapp
RUN cd /whatsapp && mkdir logs

# Tests build
RUN php -v

ENV env production
ENV timeout 60