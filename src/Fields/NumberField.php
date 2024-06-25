<?php

namespace src\Fields;

class NumberField implements FieldInterface
{
    private $name;
    private $type;
    private $value;
    private $format;

    public function __construct($name, $value = 0, $format = null)
    {
        $this->name = $name;
        $this->type = 'number';
        $this->value = $value;
        $this->format = $format;
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
        return $this->value;
    }
    public function getFormat(): ?string
    {
        return $this->format;
    }
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->format ? sprintf($this->format, $this->value) : (string) $this->value;
    }
}
