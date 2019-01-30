<?php

namespace webignition\Tests\HtmlValidator\Helper;

use webignition\HtmlValidator\Output\Parser\HeaderBodySeparator;

class FixtureLoader
{
    public static function load(string $name): string
    {
        $path = realpath(__DIR__ . '/../fixtures/' . $name);

        if (false === $path) {
            return '';
        }

        $content = file_get_contents($path);

        return false === $content
            ? ''
            : $content;
    }

    public static function loadBodyContent(string $name): string
    {
        $fixture = self::load($name);

        $headerBody = HeaderBodySeparator::separate($fixture);

        return $headerBody[HeaderBodySeparator::PART_BODY];
    }
}
