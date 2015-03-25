#!/bin/sh
iptables -t nat -A PREROUTING -i venet0:0 -p tcp --dport 8080 -j REDIRECT --to-ports 80
iptables -t nat -A OUTPUT -p tcp --dport 8080 -j REDIRECT --to-ports 80
