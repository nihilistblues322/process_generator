<?php


use src\Process;
use src\Fields\DateField;
use src\Fields\TextField;
use src\Fields\NumberField;
use src\Database\Database;
use PHPUnit\Framework\TestCase;
use src\Database\ProcessRepository;

class ProcessTest extends TestCase
{
    private $db;
    private $repository;

    protected function setUp(): void
    {
        $this->db = new Database(__DIR__ . '/database.sqlite');
        $this->repository = new ProcessRepository($this->db);
    }

    public function testCreateProcess(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $processName = 'Process-' . $this->generateRandomString(5);
            $process = new Process(null, $processName);

            $this->addFieldRandomly($process);

            $this->repository->saveProcess($process);

            $this->assertNotEmpty($process->id);
            $this->assertCount(1, $process->getFields());
        }
    }


    public function addFieldRandomly(Process $process)
    {
        $fieldType = mt_rand(1, 3);
        $randomText = $this->generateRandomString(7);
        $randomNumber = mt_rand(100, 1000) + mt_rand() / mt_getrandmax() * 100;
        $randomTimestamp = mt_rand(strtotime('2023-01-01'), strtotime('2024-12-31'));
        $randomDate = date('d.m.Y', $randomTimestamp);

        switch ($fieldType) {
            case 1:
                $process->addField(new TextField('Text', $randomText));
                break;
            case 2:
                $process->addField(new NumberField('Number', $randomNumber, '%+.2f'));
                break;
            case 3:
                $process->addField(new DateField('Date', $randomDate, 'd.m.Y'));
                break;
        }
    }


    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
