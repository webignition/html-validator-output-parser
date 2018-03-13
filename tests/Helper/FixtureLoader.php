<?php

namespace webignition\Tests\HtmlValidator\Helper;

use webignition\HtmlValidator\Output\Parser\HeaderBodySeparator;

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

    /**
     * @param string $name
     *
     * @return string
     */
    public static function loadBodyContent($name)
    {
        $fixture = self::load($name);

        $headerBody = HeaderBodySeparator::separate($fixture);

        return $headerBody[HeaderBodySeparator::PART_BODY];
    }
}
