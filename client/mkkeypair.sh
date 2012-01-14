#! /bin/bash
echo -n "Enter filename for private key:"
read fileBase 
openssl genrsa -out $fileBase 2048
openssl rsa -in $fileBase -out $fileBase.pub -pubout -outform PEM
