<?php
require_once __DIR__ . '/vendor/autoload.php';

use src\Process;
use src\Fields\DateField;
use src\Fields\TextField;
use src\Fields\NumberField;
use src\Database\ProcessRepository;

$repository = new ProcessRepository('database.sqlite');

function generateUniqueThreeDigitNumber()
{
    static $usedNumbers = [];
    do {
        $number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    } while (isset($usedNumbers[$number]));
    $usedNumbers[$number] = true;
    return $number;
}

function generateRandomText($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomText = '';
    for ($i = 0; $i < $length; $i++) {
        $randomText .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomText;
}

function RandomFieldGeneration()
{
    $fieldTypes = ['text', 'number', 'date'];
    $process = new Process('Process' . generateUniqueThreeDigitNumber());
    for ($i = 0; $i < mt_rand(2, 5); $i++) {
        $type = $fieldTypes[array_rand($fieldTypes)];
        switch ($type) {
            case 'text':
                $name = "Text" . generateUniqueThreeDigitNumber();
                $field = new TextField($name, generateRandomText());
                break;
            case 'number':
                $name = "Number" . generateUniqueThreeDigitNumber();
                $field = new NumberField($name, rand(0, 100000) / 100, '%.2f');
                break;
            case 'date':
                $name = "Date" . generateUniqueThreeDigitNumber();
                $randomDate = new DateTime(date('Y-m-d', strtotime(sprintf('%+d days', rand(-365, 365)))));
                $field = new DateField($name, $randomDate, 'd.m.Y');
                break;
        }
        $process->addField($field);
    }
    return $process;
}

$repository->save(RandomFieldGeneration());

$processes = $repository->getAll(1, 100);

foreach ($processes as $process) {
    echo "Process: {$process->getName()}\n";
    foreach ($process->getFormattedFields() as $name => $value) {
        echo "{$name}: {$value}\n";
    }
    echo "\n";
}