<?php

use src\Process;
use src\Fields\DateField;
use src\Fields\TextField;
use src\Fields\NumberField;
use PHPUnit\Framework\TestCase;



class ProcessTest extends TestCase
{
    public function testProcessCreation()
    {
        $process = new Process('Test Process');
        $this->assertInstanceOf(Process::class, $process);
    }

    public function testAddField()
    {
        $process = new Process('Test Process');
        $textField = new TextField('Text', 'Sample Text');
        $process->addField($textField);

        $fields = $process->getFields();
        $this->assertCount(1, $fields);
        $this->assertArrayHasKey('Text', $fields);
    }

    public function testGetFormattedFields()
    {
        $process = new Process('Test Process');
        $process->addField(new TextField('Text', 'Sample Text'));
        $process->addField(new NumberField('Number', 123.45, '%.2f'));
        $process->addField(new DateField('Date', new DateTime("2023-06-26"), 'd.m.Y'));

        $formatted = $process->getFormattedFields();
        $this->assertEquals('Sample Text', $formatted['Text']);
        $this->assertEquals('123.45', $formatted['Number']);
        $this->assertEquals('26.06.2023', $formatted['Date']);
    }

    
}