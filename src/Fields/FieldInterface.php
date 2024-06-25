<?php

namespace src\Fields;

interface FieldInterface
{
    public function getName(): string;
    public function getType(): string;
    public function getValue();
    public function getFormat(): ?string;
    public function setValue($value): void;
    public function __toString(): string;
}
