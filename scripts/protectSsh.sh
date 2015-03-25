#!/bin/sh

iptables -N SSHATTACK
iptables -A SSHATTACK -j LOG --log-prefix "Possible SSH attack! " --log-level 7
iptables -A SSHATTACK -j DROP

iptables -A INPUT -p tcp -m state --dport 22 --state NEW -m recent --set
iptables -A INPUT -p tcp -m state --dport 22 --state NEW -m recent --update --seconds 120 --hitcount 4 -j SSHATTACK

# 3) See log entries of possible shh- attacks in /var/log/syslog

