<?php

namespace webignition\HtmlValidator\Output\Header;

class Header
{
    /**
      * @var array
     */
    private $values = [];

    /**
     * @param string $key
     *
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->values[strtolower($key)] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return (isset($this->values[strtolower($key)])) ? $this->values[strtolower($key)] : null;
    }
}
