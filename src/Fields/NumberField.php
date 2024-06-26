<?php

namespace src\Fields;

use src\Fields\Interfaces\IField;

class NumberField implements IField
{
    private string $name;
    private float $value;
    private string $format;

    public function __construct(string $name, float $value = 0, string $format = '%d')
    {
        $this->name = $name;
        $this->value = $value;
        $this->format = $format;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return 'number';
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDefaultValue(): float
    {
        return 0;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function format(): string
    {
        return sprintf($this->format, $this->value);
    }
}