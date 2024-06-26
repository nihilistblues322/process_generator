<?php

namespace src\Fields\Interfaces;

interface IField
{
    public function getName(): string;
    public function getType(): string;
    public function getValue();
    public function getDefaultValue();
    public function format(): string;
}