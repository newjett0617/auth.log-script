#!/usr/bin/env bash

echo "running ip.php..."
php ip.php
echo "output ip list to ip.txt"

echo "output ip rank to ip-rank.txt"
sort ip.txt | uniq -c | sort > ip-rank.txt

echo "output ip unique to ip-unique.txt"
sort ip.txt | uniq | sort > ip-unique.txt
