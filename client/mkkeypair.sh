#! /bin/bash
fileBase=""
if [ $# -lt 1 ]; then
	echo -n "Enter filename for private key:"
	read fileBase 
else
	fileBase="$1"
fi
openssl genrsa -out $fileBase 2048
openssl rsa -in $fileBase -out $fileBase.pub -pubout -outform PEM
