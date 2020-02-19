#!/bin/sh

bash /wait-for-it.sh $DB_HOST:$DB_PORT -t 30

#tail -f /dev/null

/wow/bin/worldserver -c /etc/wow/worldserver.conf