<?php

namespace src\Fields;

use src\Fields\Interfaces\IField;

class TextField implements IField
{
    private string $name;
    private string $value;

    public function __construct(string $name, string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return 'text';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return '';
    }

    public function format(): string
    {
        return $this->value;
    }
}
