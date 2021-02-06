#!/usr/bin/env bash

echo "running user.php..."
php user.php
echo "output user list to user.txt"

echo "output user rank to user-rank.txt"
sort user.txt | uniq -c | sort > user-rank.txt

echo "output  unique to user-unique.txt"
sort user.txt | uniq | sort > user-unique.txt
