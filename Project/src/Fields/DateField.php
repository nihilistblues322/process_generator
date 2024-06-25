<?php

namespace src\Fields;

use DateTime;

class DateField implements FieldInterface
{
    private $name;
    private $type;
    private $value;
    private $format;
    private $dateFormat;

    public function __construct($name, $value = null, $dateFormat = 'Y-m-d')
    {
        $this->name = $name;
        $this->type = 'date';
        $this->value = $value ? new DateTime($value) : new DateTime();
        $this->dateFormat = $dateFormat;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value->format('Y-m-d');
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    public function setValue($value): void
    {
        $this->value = new DateTime($value);
    }

    public function __toString(): string
    {
        return $this->value->format($this->dateFormat);
    }
}
