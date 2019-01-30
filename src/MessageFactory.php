<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\HtmlValidatorOutput\Models\InfoMessage;
use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\ValidatorMessage\MessageInterface;

class MessageFactory
{
    const KEY_TYPE = 'type';
    const KEY_MESSAGE = 'message';
    const KEY_MESSAGE_ID = 'messageid';
    const KEY_EXPLANATION = 'explanation';
    const KEY_LINE_NUMBER = 'lastLine';
    const KEY_COLUMN_NUMBER = 'lastColumn';

    public function createMessageFromArray(array $values)
    {
        $type = $values[self::KEY_TYPE];

        if (MessageInterface::TYPE_INFO === $type) {
            return $this->createInfoMessageFromArray($values);
        }

        return $this->createValidationErrorMessageFromArray($values);
    }

    private function createValidationErrorMessageFromArray(array $values)
    {
        return new ValidationErrorMessage(
            $values[self::KEY_MESSAGE],
            $values[self::KEY_MESSAGE_ID],
            $values[self::KEY_EXPLANATION],
            (int) $values[self::KEY_LINE_NUMBER],
            (int) $values[self::KEY_COLUMN_NUMBER]
        );
    }

    private function createInfoMessageFromArray(array $values)
    {
        return new InfoMessage(
            $values[self::KEY_MESSAGE],
            $values[self::KEY_MESSAGE_ID],
            $values[self::KEY_EXPLANATION]
        );
    }
}
