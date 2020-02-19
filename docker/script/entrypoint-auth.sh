#!/bin/sh

bash /wait-for-it.sh $DB_HOST:$DB_PORT -t 30

/wow/bin/authserver -c /etc/wow/authserver.conf