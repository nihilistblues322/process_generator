<?php

use src\Process;
use src\Fields\DateField;
use src\Fields\TextField;
use src\Database\Database;
use src\Fields\NumberField;
use src\Database\ProcessRepository;

require 'vendor/autoload.php';

$path = __DIR__ . '/database.sqlite';

$db = new Database($path);
$repository = new ProcessRepository($db);


$fieldType = mt_rand(1, 3); 
$processName = 'Process-' . generateRandomString(5);
$randomText = generateRandomString();
$randomNumber = mt_rand(1, 1000) / 10;
$randomDate = date('Y-m-d', mt_rand(strtotime('2000-01-01'), strtotime('2023-12-31')));


$process = new Process(null, $processName);


switch ($fieldType) {
    case 1:
        $process->addField(new TextField('Text', $randomText));
        break;
    case 2:
        $process->addField(new NumberField('Number', $randomNumber, '%+.2f'));
        break;
    case 3:
        $process->addField(new DateField('Date', $randomDate, 'Y-m-d'));
        break;
}


$repository->saveProcess($process);


$processes = $repository->getProcesses(100, 0);

foreach ($processes as $proc) {
    echo "Process ID: " . $proc['id'] . "\n";
    echo "Process Name: " . $proc['name'] . "\n";
}

function generateRandomString($length = 10)
{
    $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
