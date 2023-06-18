<?php

namespace SimpleMvc;

/**
 * Class BaseModel
 *
 * This class represents the base model for all domain objects.
 * It is a simple data structure that holds data and provides methods to access and manipulate it.
 */
abstract class BaseModel implements \JsonSerializable, \ArrayAccess
{

    /**
     * @param $propertyName
     * @return mixed
     * @throws \RuntimeException
     */
    public function __get($propertyName): mixed
    {
        if (property_exists(get_class($this), $propertyName)) {
            return $this->{$propertyName};
        }
        throw new \RuntimeException("Attribute " . $propertyName . " does not exist in class " . get_class($this));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \Exception
     */
    public function __set(string $name, mixed $value): void
    {
        if (property_exists(get_class($this), $name)) {
            if (gettype($value) === gettype($this->{$name})) {
                $this->{$name} = $value;
            } else {
                throw new \RuntimeException("Attribute $name expects type " . gettype($this->{$name}) . ", " . gettype($value) . " given.");
            }
        } else {
            throw new \RuntimeException("Attribute " . $name . " does not exist in class " . get_class($this));
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->{$name});
    }

    /**
     * Maps array keys and values to Domain object properties
     * @param $array
     */
    public static function from($array): object
    {
        $className = static::class;
        $entry = new $className();
        foreach ($array as $key => $value) {
            $entry->{$key} = $value;
        }
        return $entry;
    }

    /**
     * Maps Domain object properties to an assoc array
     * @param $object
     * @return array
     */
    public static function toArray($object): array
    {
        if (is_array($object) || is_object($object)) {
            $result = [];
            foreach ($object as $key => $value) {
                $result[$key] = (is_array($value) || is_object($value)) ? self::toArray($value) : $value;
            }
            return $result;
        }
        return $object;
    }

    // =================================
    // implemented methods
    // =================================

    public function jsonSerialize(): array
    {
        return self::toArray($this);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }
}