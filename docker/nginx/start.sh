#!/bin/sh
# echo "npm run"
# npm run dev -- --host &
echo "Starting Supervisor"
echo "####################"

/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf &

echo "####################"
echo "Starting services..."
service php8.2-fpm start
nginx -g 'daemon off;'
echo "Ready."
tail -s 1 /var/log/nginx/*.log -f