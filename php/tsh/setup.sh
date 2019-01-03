#!/bin/bash
echo Please enter Tsh_port
read port
echo Got port: $port
echo "export TSH_PORT=$port" >> ~/.bashrc
#sed -i  s/#tshPORT/$port/g ~/.bashrc
source ~/.bashrc
