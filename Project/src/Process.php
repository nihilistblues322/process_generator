<?php

namespace src;

use src\Fields\FieldInterface;


class Process
{
    public $id;
    public $name;
    private $fields = [];

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function addField(FieldInterface $field): void
    {
        $this->fields[$field->getName()] = $field;
        
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function __toString(): string
    {
        $result = "Process: {$this->name}\n";
        foreach ($this->fields as $field) {
            $result .= "{$field->getName()} ({$field->getType()}): {$field}\n";
        }
        return $result;
    }
}
