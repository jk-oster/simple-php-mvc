<?php

namespace SimpleMvc;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Collection
 *
 * Inspired by Laravel's Collection class
 * Enables functional programming on arrays
 *
 * @property array $items
 * @example
 * $collection = new Collection([1, 2, 3]);
 * $collection->map(fn($item) => $item * 2); // [2, 4, 6]
 * $collection->filter(fn($item) => $item > 2); // [3]
 * $collection->reduce(fn($carry, $item) => $carry + $item); // 6
 * $collection->join(','); // '1,2,3'
 * $collection->pipe(fn($collection) => $collection->map(fn($item) => $item * 2))->join(','); // '2,4,6'
 */
class Collection implements JsonSerializable, ArrayAccess, IteratorAggregate, Countable
{
    protected array $items;

    public function __construct(array|Collection $items = [])
    {
        // Check if items is another Collection instance
        if($items instanceof self) {
            // Convert that Collection's items to an array
            $this->items = $items->toArray();
        } else {
            // Else consider it as an array
            $this->items = is_array($items) ? $items : [];
        }
    }

    // =================================
    // Magic methods
    // =================================

    public function __toString(): string
    {
        return $this->join('');
    }

    // Is called when calling the object as a function
    public function __invoke($fn = null): array
    {
        // depending on use-case
        if(is_callable($fn)) {
            return $this->map($fn)->toArray();
        }

        return $this->items;
    }

    public function __get($key): mixed
    {
        return $this->items[$key];
    }

    public function __set($key, $value): void
    {
        $this->items[$key] = $value;
    }

    public function __isset($key): bool
    {
        return isset($this->items[$key]);
    }

    public function __unset($key): void
    {
        unset($this->items[$key]);
    }

    public function __debugInfo(): array
    {
        return $this->items;
    }

    public function __serialize(): array
    {
        return $this->items;
    }

    public function __unserialize(array $items): void
    {
        $this->items = $items;
    }

    public static function __set_state(array $items): Collection
    {
        return new Collection($items);
    }

    public function __clone(): void
    {
        $this->items = array_map(static function ($item) {
            return clone $item;
        }, $this->items);
    }

    // =================================
    // JsonSerializable
    // =================================

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    // =================================
    // ArrayAccess
    // =================================

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->__unset($offset);
    }

    // =================================
    // Countable, IteratorAggregate
    // =================================

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    // ========================================
    // Collection methods
    // ========================================

    public static function collect(array $items): Collection
    {
        return new Collection($items);
    }

    public function map(callable $fn): Collection
    {
        return new Collection(array_map($fn, $this->items));
    }

    public function filter(callable $fn, $mode = 0): Collection
    {
        return new Collection(array_filter($this->items, $fn, $mode));
    }

    public function reject(callable $fn): Collection
    {
        return $this->filter(function ($item) use ($fn) {
            return !$fn($item);
        });
    }

    public function keys(): Collection
    {
        return new Collection(array_keys($this->items));
    }

    public function values(): Collection
    {
        return new Collection(array_values($this->items));
    }

    public function merge(array|Collection $items): Collection
    {
        return new Collection(array_merge($this->items, $items instanceof self ? $items->toArray() : $items));
    }

    public function mergeRecursive(array|Collection $items): Collection
    {
        return new Collection(array_merge_recursive($this->items, $this->items, $items instanceof self ? $items->toArray() : $items));
    }

    public function take(int $n): Collection
    {
        return new Collection(array_slice($this->items, 0, $n));
    }

    public function skip(int $n): Collection
    {
        return new Collection(array_slice($this->items, $n));
    }

    public function slice(int $start, int $end, bool $preserveKeys): Collection
    {
        return new Collection(array_slice($this->items, $start, $end, $preserveKeys));
    }

    public static function range(int $start, int $end): Collection
    {
        return new Collection(range($start, $end));
    }

    public function chunk(int $n, bool $preserveKeys = false): Collection
    {
        return new Collection(array_chunk($this->items, $n, $preserveKeys));
    }

    public function pluck(string $key): Collection
    {
        return $this->map(function ($item) use ($key) {
            return $item[$key];
        });
    }

    public function unique(): Collection
    {
        return new Collection(array_unique($this->items));
    }

    public function flatten(): Collection
    {
        return new Collection(array_reduce($this->items, static function ($result, $item) {
            return array_merge($result, $item);
        }, []));
    }

    public function flip(): Collection
    {
        return new Collection(array_flip($this->items));
    }

    public function sort(callable $fn): Collection
    {
        $items = $this->items;
        usort($items, $fn);
        return new Collection($items);
    }

    public function reverse(): Collection
    {
        return new Collection(array_reverse($this->items));
    }

    public function shuffle(): Collection
    {
        $items = $this->items;
        shuffle($items);
        return new Collection($items);
    }

    public function groupBy(callable $fn): Collection
    {
        return new Collection(array_reduce($this->items, static function ($result, $item) use ($fn) {
            $key = $fn($item);
            if (!isset($result[$key])) {
                $result[$key] = [];
            }
            $result[$key][] = $item;
            return $result;
        }, []));
    }

    // ==========================================================
    // Aggregations, Reducers, and Iterators
    // ==========================================================

    public function pipe(callable ...$fns): mixed
    {
        return array_reduce($fns, static function($collection, $fn) {
            return $fn($collection);
        }, $this);
    }

    public function reduce(callable $fn, $initial = null): mixed
    {
        return array_reduce($this->items, $fn, $initial);
    }

    public function sum(): int
    {
        return array_sum($this->items);
    }

    public function avg(): float
    {
        return array_sum($this->items) / count($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function join(string $glue): string
    {
        return implode($glue, $this->items);
    }

    // Get the first item also of associative arrays
    public function first(): mixed
    {
        if(empty($this->items)) {
            return null;
        }
        if(array_key_exists(0, $this->items)) {
            return $this->items[0];
        }
        reset($this->items);
        $firstKey = key($this->items);
        return $this->items[$firstKey];
    }

    public function last(): mixed
    {
        return $this->items[count($this->items) - 1];
    }

    public function every(callable $fn): bool
    {
        foreach ($this->items as $item) {
            if (!$fn($item)) {
                return false;
            }
        }
        return true;
    }

    public function some(callable $fn): bool
    {
        foreach ($this->items as $item) {
            if ($fn($item)) {
                return true;
            }
        }
        return false;
    }

    public function includes(mixed $value): bool
    {
        return in_array($value, $this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function toArray(): array
    {
        return $this->items;
    }
}