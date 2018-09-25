<?php

namespace webignition\HtmlValidator\Output\MessageExcluder;

class MessageExcluder
{
    const AMPERSAND_ENCODING_MESSAGE =
        '& did not start a character reference. (& probably should have been escaped as &amp;.)';

    const CSS_ERROR_MESSAGE_PATTERN = '/^CSS:/';

    /**
     * @var bool
     */
    private $ignoreAmpersandEncodingIssues = false;

    /**
     * @var bool
     */
    private $ignoreCssValidationIssues = false;

    /**
     * @param bool $ignoreAmpersandEncodingIssues
     */
    public function setIgnoreAmpersandEncodingIssues($ignoreAmpersandEncodingIssues)
    {
        $this->ignoreAmpersandEncodingIssues = $ignoreAmpersandEncodingIssues;
    }

    /**
     * @param bool $ignoreCssValidationIssues
     */
    public function setIgnoreCSSValidationIssues($ignoreCssValidationIssues)
    {
        $this->ignoreCssValidationIssues = $ignoreCssValidationIssues;
    }

    public function isMessageExcluded(\stdClass $message)
    {
        if ($this->ignoreAmpersandEncodingIssues && self::AMPERSAND_ENCODING_MESSAGE === $message->message) {
            return true;
        }

        if ($this->ignoreCssValidationIssues && preg_match(self::CSS_ERROR_MESSAGE_PATTERN, $message->message)) {
            return true;
        }

        return false;
    }
}
