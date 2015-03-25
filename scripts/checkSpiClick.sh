#!/bin/sh
nb=`ps aux | grep spiclick$ | wc -l`

if [ 2 -gt $nb ]
then
	bash /root/spiclick.sh # create

	iptables --flush

	echo "created new spiclick"
	iptables -A INPUT -p tcp -s 127.0.0.0/8 --dport 1100 -j ACCEPT
	iptables -A INPUT -p tcp -s 127.0.0.0/8 --dport 1101 -j ACCEPT
	iptables -A INPUT -p tcp --dport 1100 -j DROP
	iptables -A INPUT -p tcp --dport 1101 -j DROP
fi
