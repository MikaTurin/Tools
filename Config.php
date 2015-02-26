<?php

namespace App;

class Config implements \ArrayAccess
{
    protected $items = array();

    public function __construct(array $items = array())
    {
        $this->set($items);
    }

    public function get($key, $default = null)
    {
        $segs = explode('.', $key);
        $root = $this->items;

        foreach ($segs as $part) {
            if (isset($root[$part])) {
                $root = $root[$part];
                continue;
            } else {
                $root = $default;
                break;
            }
        }

        return $root;
    }

    public function set($key, $value = null)
    {
        if (!is_array($key)) {
            $this->add($key, $value);
            return;
        }

        foreach ($key as $k => $v) {
            $this->add($k, $v);
        }
    }

    protected function add($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $this->add($key.'.'.$k, $v);
            }
            return;
        }

        $segs = explode('.', $key);
        $root = &$this->items;

        while ($part = array_shift($segs)) {
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = array();
            }
            $root = &$root[$part];
        }

        $root = $value;
    }

    public function all()
    {
        return $this->items;
    }

    public function offsetExists($key)
    {
        return !is_null($this->get($key));
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->set($key, null);
    }
}