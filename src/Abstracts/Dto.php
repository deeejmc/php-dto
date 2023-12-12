<?php

namespace Deeejmc\PhpDto\Abstracts;

use Deeejmc\PhpDto\Contracts\Dto as DtoContract;

abstract class Dto implements DtoContract
{
    /**
     * @var array
     */
    protected array $mapped = [];

    /**
     * @param array|null $attributes
     * @param array|null $mapped
     */
    public function __construct(
        array $attributes = null,
        array $mapped = null
    ) {
        if ($mapped) {
            $this->map($mapped);
        }
        if ($attributes) {
            $this->fill($attributes);
        }
    }

    /**
     * In cases where an attribute has been provided but the key doesn't match the property
     * in the DTO, you can specify a mapping that will tell the DTO to use another property
     * key instead. This avoids having variations of one variable and keeps your DTO clean. 
     * 
     * Here's an example of mapping a property to an attribute with a different key:
     * 
     * $user = new User();
     * 
     * $user->map([
     *    'email' => 'email_address',
     * ]);
     * 
     * $user->fill([
     *    'email_address' => 'john.doe@gmail.com',
     * ]);
     * 
     * In our DTO, 'email_address' doesn't exist, but 'email' does. By mapping 'email' to
     * 'email_address', the DTO will to set the value of 'email_address' to the 'email'
     * property. This value can then be retrieved by calling `$user->email`.
     * 
     * @param array $attributes
     * 
     * @return DtoContract
     */
    public function map(array $attributes): DtoContract
    {
        $this->mapped = $attributes;

        return $this;
    }

    /**
     * This populates the DTO with the attributes provided.
     * 
     * By default, this only happens for the attributes you provide a value for, however
     * as some properties have an override function and may need triggering regardless,
     * we then loop through all of the null or empty properties left in the DTO and
     * attempt to populate them. For the majority, the value will still be null.
     * 
     * @param array $attributes
     * 
     * @return DtoContract
     */
    public function fill(array $attributes): DtoContract
    {
        $this->_populate($attributes);

        $this->_populate(array_filter($this->toArray(), [$this, '_isEmpty']));

        unset($this->mapped);

        return $this;
    }

    /**
     * @return object
     */
    public function toObject(): object
    {
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param array $attributes
     * 
     * @return void
     */
    private function _populate(array $attributes)
    {
        foreach ($attributes as $key => $value) {

            // property keys should always be camel case, so whether you write your
            // attributes in snake case or not, it'll always be converted
            $property = $this->_snakeToCamel(

                // use the mapped key if it exists
                array_search($key, $this->mapped) ?: $key
            );

            // make the first letter of the property uppercase for the method name
            $method = ucfirst($property);

            // if the property doesn't exist, skip to the next one
            if (!property_exists($this, $property)) {
                continue;
            }

            if (method_exists($this, "set{$method}")) {

                // if an override method exists, call that
                $this->{"set{$method}"}($value);
            } else {

                // otherwise, populate the property with the current value
                $this->{$property} = $value;
            }
        }
    }

    /**
     * @param string $input
     * 
     * @return string
     */
    private function _snakeToCamel(string $input)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    /**
     * @param mixed $attribute
     * 
     * @return bool
     */
    private function _isEmpty(mixed $attribute)
    {
        return (bool) !$attribute;
    }
}