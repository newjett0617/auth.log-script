<?php

$counterMax = -1;

$path = __DIR__ . '/';

$files = [
    $path . 'auth.log.3',
    $path . 'auth.log.4',
];

$ipOutput = $path . 'ip.txt';
file_put_contents($ipOutput, '');

foreach ($files as $file) {
    $file = new SplFileObject($file);
    $counter = 0;

    while (!$file->eof()) {
        ++$counter;

        $line = strtolower($file->fgets());

        if (!preg_match('/sshd/i', $line)) {
            continue;
        }

        $ip = processIp($line);
        if ($ip === false) {
            continue;
        }
        file_put_contents($ipOutput, $ip . PHP_EOL, FILE_APPEND);

        if ($counter === $counterMax) {
            return;
        }
    }

    $file = null;
}

function processIp($line)
{
    $re = '/(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}/m';

    if (preg_match($re, $line, $matches)) {
        return $matches[0];
    } else {
        return false;
    }
}
