<?php

namespace SimpleMvc\Framework;

use Exception;
use JsonSerializable;

/**
 * Models a simple DataClass (Domain). Provides:
 * - magic GETTER
 * - magic SETTER
 * - static method to map an array to Domain Object
 *
 * @author Jakob Osterberger
 * @date 13.06.2022
 */
abstract class BaseModel implements JsonSerializable
{
    // Required fields for every model
    protected int $id;
    protected string $created;
    protected string $edited;

    public function __construct()
    {
    }

    /**
     * @param $propertyName
     * @return mixed
     * @throws Exception
     */
    public function __get($propertyName): mixed
    {
        if (property_exists(get_class($this), $propertyName)) return $this->{$propertyName};
        else throw new Exception("Attribut " . $propertyName . " does not exist in class " . get_class($this));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    public function __set(string $name, mixed $value): void
    {
        if (property_exists(get_class($this), $name)) {
            $type = gettype($this->{$name});
            $this->{$name} = $value;
            settype($this->{$name}, $type);
        } else throw new Exception("Attribute " . $name . " does not exist in class " . get_class($this));
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
     * @return \BaseModel Domain object with properties set to array values
     */
    public static function objectFrom($array): mixed
    {
        $className = static::class;
        $entry = new $className();
        foreach ($array as $key => $value) {
            $entry->{$key} = $value;
        }
        return $entry;
    }

    /**
     * Maps Domain object properties recursively to an assoc array
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

    public function jsonSerialize(): mixed
    {
        return self::toArray($this);
    }
}
