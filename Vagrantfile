
Vagrant.configure("2") do |config|
    config.vm.provider :libvirt do |libvirt|
        libvirt.cpus = 1
        libvirt.memory = 1024
    end

    config.vm.box = "generic/ubuntu1804"
    config.vm.hostname = "timerboard"

    config.vm.synced_folder "./", "/var/www/timerboard", type: "rsync", 
        rsync__exclude: [".env", "vendor", ".idea", ".jshintrc"]

    config.ssh.username = 'vagrant'
    config.ssh.password = 'vagrant'

    # run server setup as root
    config.vm.provision "shell", inline: <<-SHELL
        PASSWD='vagrant'
        export DEBIAN_FRONTEND=noninteractive
        echo "LC_ALL=en_US.UTF-8" > /etc/environment

        apt-get update
        apt-get upgrade -y
        apt-get autoremove -y

        # install PHP + Composer
        apt-get install -y php php7.2 php7.2-fpm
        apt-get install -y php-cli php-curl php-xml php-json php-mbstring php-mysql php-xdebug
        apt-get install -y composer

        # install MySQL server
        debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWD"
        debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWD"
        apt-get install -y mysql-server
        mysql -e 'CREATE DATABASE IF NOT EXISTS timerboard' -p$PASSWD

        # install Apache + virtual host
        apt-get install apache2 -y
        echo "<VirtualHost *:80>"                        > /etc/apache2/sites-available/010-timerboard.conf
        echo "    DocumentRoot /var/www/timerboard/web" >> /etc/apache2/sites-available/010-timerboard.conf
        echo "    <Directory /var/www/timerboard/web/>" >> /etc/apache2/sites-available/010-timerboard.conf
        echo "        AllowOverride All"                >> /etc/apache2/sites-available/010-timerboard.conf
        echo "    </Directory>"                         >> /etc/apache2/sites-available/010-timerboard.conf
        echo "</VirtualHost>"                           >> /etc/apache2/sites-available/010-timerboard.conf
        a2enmod rewrite proxy_fcgi setenvif
        a2ensite 010-timerboard
        a2dissite 000-default
        a2enconf php7.2-fpm

        # install Adminer (also installs php-pgsql, php-sqlite3)
        apt-get install -y adminer
        echo "Alias /adminer /usr/share/adminer/" > /etc/apache2/sites-available/020-adminer.conf
        a2ensite 020-adminer

        systemctl restart apache2

        # install Heroku
        sudo snap install heroku --classic
    SHELL

    # run app setup as an unprivileged user
    config.vm.provision "up", type: "shell", run: "always", privileged: false, inline: <<-SHELL
        cd /var/www/timerboard
        cp .env.dist .env
        composer install
        composer compile

        echo " "
        echo "--------------------------------------------------------------------------------"
        echo "-- URLs (change IP as needed):                                                --"
        echo "-- TimerBoard  http://192.168.121.116                                         --"
        echo "-- Adminer: http://192.168.121.116/adminer/adminer/designs.php (root/vagrant) --"
        echo "-- SSH user: vagrant/vagrant                                                  --"
        echo "-- mount: sshfs vagrant@192.168.121.116:/ /mnt/timerboard                     --"
        echo "-- unmount: fusermount -u /mnt/timerboard                                     --"
        echo "-- $ ifconfig eth0 | grep inet:                                               --"
        /sbin/ifconfig eth0 | grep "inet "
        echo "--------------------------------------------------------------------------------"
    SHELL
end
