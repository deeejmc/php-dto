<?php

namespace Deeejmc\PhpDto\Contracts;

interface Dto
{
    public function map(array $attributes): self;

    public function fill(array $attributes): self;

    public function toObject(): object;

    public function toArray(): array;
}