<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\HtmlValidatorOutput\Models\ValidatorErrorMessage;
use webignition\ValidatorMessage\MessageList;

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

    public function filter(MessageList $messageList)
    {
        $ignoreAmpersandEncodingIssues = $this->ignoreAmpersandEncodingIssues;
        $ignoreCssValidationIssues = $this->ignoreCssValidationIssues;

        return $messageList->filter(function (
            $message
        ) use (
            $ignoreAmpersandEncodingIssues,
            $ignoreCssValidationIssues
        ) {
            if ($message instanceof ValidationErrorMessage) {
                if ($ignoreAmpersandEncodingIssues && self::AMPERSAND_ENCODING_MESSAGE === $message->getMessage()) {
                    return false;
                }

                if ($ignoreCssValidationIssues && preg_match(self::CSS_ERROR_MESSAGE_PATTERN, $message->getMessage())) {
                    return false;
                }

                return true;
            }

            if ($message instanceof ValidatorErrorMessage) {
                return true;
            }

            return false;
        });
    }
}
