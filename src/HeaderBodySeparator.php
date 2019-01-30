<?php

namespace webignition\HtmlValidatorOutput\Parser;

class HeaderBodySeparator
{
    const PART_HEADER = 'header';
    const PART_BODY = 'body';

    public static function separate(string $htmlValidatorOutput): array
    {
        $headerBody = explode("\n\n", str_replace("\r\n", "\n", $htmlValidatorOutput), 2);

        return [
            self::PART_HEADER => $headerBody[0],
            self::PART_BODY => $headerBody[1],
        ];
    }
}
