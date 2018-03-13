<?php

namespace webignition\HtmlValidator\Output\Parser;

class HeaderBodySeparator
{
    const PART_HEADER = 'header';
    const PART_BODY = 'body';

    /**
     * @param string $htmlValidatorOutput
     *
     * @return array
     */
    public static function separate($htmlValidatorOutput)
    {
        $headerBody = explode("\n\n", str_replace("\r\n", "\n", $htmlValidatorOutput), 2);

        return [
            self::PART_HEADER => $headerBody[0],
            self::PART_BODY => $headerBody[1],
        ];
    }
}
