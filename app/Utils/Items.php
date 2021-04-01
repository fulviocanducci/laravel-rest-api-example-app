<?php

namespace App\Utils;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class Items implements IteratorAggregate, Countable
{
    protected $items = array();

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function add(string $key, string $value): Items
    {
        if (array_key_exists($key, $this->items) === false) {
            $this->items[$key] = $value;
            return $this;
        }
        return throw new \Exception("Existing key: {$key}", 500);
    }

    public function remove(string $key): bool
    {
        if (array_key_exists($key, $this->items)) {
            unset($this->items[$key]);
            return true;
        }
        return false;
    }

    public function merger($key, $value)
    {
        if (array_key_exists($key, $this->items)) {
            $this->items[$key] .= $value;
        }
        return $this;
    }

    public function only(array $values)
    {
        if (count($values) == 0) {
            return $this->items;
        }
        $source = array();
        foreach ($this->items as $key => $value) {
            if (in_array($key, $values)) {
                $source[$key] = $this->items[$key];
            }
        }
        return $source;
    }

    public function except(array $values)
    {
        if (count($values) == 0) {
            return $this->items;
        }
        $source = array();
        foreach ($this->items as $key => $value) {
            if (in_array($key, $values)) {
                continue;
            } else {
                $source[$key] = $this->items[$key];
            }
        }
        return $source;
    }
}
