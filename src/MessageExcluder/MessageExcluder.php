<?php

namespace webignition\HtmlValidator\Output\MessageExcluder;

use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;

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


    public function setIgnoreAmpersandEncodingIssues(bool $ignoreAmpersandEncodingIssues)
    {
        $this->ignoreAmpersandEncodingIssues = $ignoreAmpersandEncodingIssues;
    }

    public function setIgnoreCSSValidationIssues(bool $ignoreCssValidationIssues)
    {
        $this->ignoreCssValidationIssues = $ignoreCssValidationIssues;
    }

    public function isExcluded(ValidationErrorMessage $message): bool
    {
        if ($this->ignoreAmpersandEncodingIssues && self::AMPERSAND_ENCODING_MESSAGE === $message->getMessage()) {
            return true;
        }

        if ($this->ignoreCssValidationIssues && preg_match(self::CSS_ERROR_MESSAGE_PATTERN, $message->getMessage())) {
            return true;
        }

        return false;
    }
}
