<?php

namespace src;

use src\Fields\Interfaces\IField;


class Process
{
    private ?int $id;
    private string $name;
    private array $fields = [];

    public function __construct(string $name, ?int $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addField(IField $field): void
    {
        $this->fields[$field->getName()] = $field;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getFormattedFields(): array
    {
        $formatted = [];
        foreach ($this->fields as $field) {
            $formatted[$field->getName()] = $field->format();
        }
        return $formatted;
    }
}