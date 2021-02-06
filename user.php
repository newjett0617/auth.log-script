<?php

$counterMax = 100;

$path = __DIR__ . '/';

$files = [
    $path . 'auth.log.3',
    $path . 'auth.log.4',
];

$userOutput = $path . 'user.txt';
file_put_contents($userOutput, '');

foreach ($files as $file) {
    $file = new SplFileObject($file);
    $counter = 0;

    while (!$file->eof()) {
        ++$counter;

        $line = strtolower($file->fgets());

        if (!preg_match('/sshd/i', $line)) {
            continue;
        }

        $user = processUser($line);
        if ($user === false) {
            continue;
        }
        file_put_contents($userOutput, $user . PHP_EOL, FILE_APPEND);

        if ($counter === $counterMax) {
            return;
        }
    }

    $file = null;
}

function processUser($line)
{
    /*

Feb  6 04:23:03 li1695-116 sshd[2290]: Invalid user jianzuoyi from 120.70.100.88 port 33621
Feb  6 04:23:03 li1695-116 sshd[2290]: input_userauth_request: invalid user jianzuoyi [preauth]
Feb  6 04:23:03 li1695-116 sshd[2290]: pam_unix(sshd:auth): check pass; user unknown
Feb  6 04:23:03 li1695-116 sshd[2290]: pam_unix(sshd:auth): authentication failure; logname= uid=0 euid=0 tty=ssh ruser= rhost=120.70.100.88
Feb  6 04:23:05 li1695-116 sshd[2290]: Failed password for invalid user jianzuoyi from 120.70.100.88 port 33621 ssh2
Feb  6 04:23:05 li1695-116 sshd[2290]: Received disconnect from 120.70.100.88 port 33621:11: Bye Bye [preauth]
Feb  6 04:23:05 li1695-116 sshd[2290]: Disconnected from 120.70.100.88 port 33621 [preauth]

    這是一個使用者嘗試連線的 log
     */

    $re = '/failed password for invalid user (.*) from/';

    if (preg_match($re, $line, $matches)) {
        return $matches[1];
    }
    return false;
}
