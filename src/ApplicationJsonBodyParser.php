<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\ValidatorMessage\MessageList;

class ApplicationJsonBodyParser
{
    const KEY_MESSAGES = 'messages';

    const AMPERSAND_ENCODING_MESSAGE =
        '& did not start a character reference. (& probably should have been escaped as &amp;.)';

    /**
     * @var MessageExcluder
     */
    private $messageExcluder;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    public function __construct(Configuration $configuration)
    {
        $this->messageExcluder = MessageExcluderFactory::create($configuration);
        $this->messageFactory = new MessageFactory();
    }

    public function parse(string $content): MessageList
    {
        $messageList = new MessageList();

        $content = $this->fixInvalidJson($content);
        $decodedContent = json_decode($content, true);

        if (!is_array($decodedContent) || !array_key_exists(self::KEY_MESSAGES, $decodedContent)) {
            return $messageList;
        }

        $messages = $decodedContent[self::KEY_MESSAGES];
        foreach ($messages as $index => $messageValues) {
            if (is_array($messageValues)) {
                $message = $this->messageFactory->createMessageFromArray($messageValues);

                if ($message instanceof ValidationErrorMessage && !$this->messageExcluder->isExcluded($message)) {
                    $messageList->addMessage($message);
                }
            }
        }

        return $messageList;
    }

    private function fixInvalidJson(string $htmlValidatorBodyContent): string
    {
        $emptyMessage = '"message": ,';
        $nonEmptyMessage = '"message": "",';

        if (substr_count($htmlValidatorBodyContent, $emptyMessage)) {
            $htmlValidatorBodyContent = str_replace($emptyMessage, $nonEmptyMessage, $htmlValidatorBodyContent);
        }

        return $htmlValidatorBodyContent;
    }
}
