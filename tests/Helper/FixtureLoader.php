<?php

namespace webignition\Tests\HtmlValidator\Helper;

class FixtureLoader
{
    /**
     * @param string $name
     *
     * @return string
     */
    public static function load($name)
    {
        return file_get_contents(realpath(__DIR__ . '/../fixtures/' . $name));
    }
}
