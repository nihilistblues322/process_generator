<?php

namespace src\Fields;

use DateTime;
use src\Fields\Interfaces\IField;

class DateField implements IField
{
    private string $name;
    private DateTime $value;
    private string $format;

    public function __construct(string $name, ?DateTime $value = null, string $format = 'Y-m-d')
    {
        $this->name = $name;
        $this->value = $value ?? new DateTime();
        $this->format = $format;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return 'date';
    }

    public function getValue(): DateTime
    {
        return $this->value;
    }

    public function getDefaultValue(): DateTime
    {
        return new DateTime();
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function format(): string
    {
        return $this->value->format($this->format);
    }
}