<?php

namespace Deeejmc\PhpDto\Contracts;

interface Dto
{
    /**
     * @param array $attributes
     * 
     * @return self
     */
    public function map(array $attributes): self;

    /**
     * @param array $attributes
     * 
     * @return self
     */
    public function fill(array $attributes): self;

    /**
     * @return object
     */
    public function toObject(): object;

    /**
     * @param bool $convertKeysToSnakeCase
     * 
     * @return array
     */
    public function toArray(bool $convertKeysToSnakeCase = true): array;
}