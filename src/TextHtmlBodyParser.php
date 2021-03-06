<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\HtmlValidatorOutput\Models\ValidatorErrorMessage;
use webignition\ValidatorMessage\MessageList;

class TextHtmlBodyParser
{
    const SOFTWARE_ERROR_HEADING_CONTENT = 'Software error:';

    public function parse(string $htmlValidatorBodyContent): MessageList
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlValidatorBodyContent);

        if ($this->isValidatorSoftwareError($dom)) {
            return new MessageList([
                new ValidatorErrorMessage(
                    'Sorry, this document can\'t be checked',
                    'validator-internal-server-error'
                ),
            ]);
        }

        $fatalErrorsElement = $dom->getElementById('fatal-errors');

        if ($fatalErrorsElement instanceof \DOMElement) {
            $fatalErrorMessageList = $this->createFatalErrorMessageList($fatalErrorsElement);
            $messages = $fatalErrorMessageList->getMessages();
            $firstMessage = current($messages);

            $isCharacterEncodingError = substr_count(
                $firstMessage->getMessage(),
                'contained one or more bytes that I cannot interpret'
            ) === 1;

            if ($isCharacterEncodingError) {
                return new MessageList([
                    $firstMessage->withMessageId('character-encoding'),
                ]);
            }

            return $fatalErrorMessageList;
        }

        return new MessageList([
            $this->createValidatorUnknownErrorMessage(),
        ]);
    }

    private function isValidatorSoftwareError(\DOMDocument $dom): bool
    {
        /* @var \DOMNodeList */
        $levelOneHeadings = $dom->getElementsByTagName('h1');
        $levelOneHeading = $levelOneHeadings->item(0);

        return $levelOneHeading instanceof \DOMElement
            ? self::SOFTWARE_ERROR_HEADING_CONTENT === trim($levelOneHeading->textContent)
            : false;
    }

    private function createValidatorUnknownErrorMessage(): ValidatorErrorMessage
    {
        return new ValidatorErrorMessage('An unknown error occurred', 'unknown');
    }

    private function createFatalErrorMessageList(\DOMElement $fatalErrorsElement): MessageList
    {
        $messages = [];

        $errorContainers = $fatalErrorsElement->getElementsByTagName('li');

        $outputObject = new \stdClass();
        $outputObject->messages = [];

        foreach ($errorContainers as $errorContainer) {
            /* @var $errorContainer \DOMElement */
            $currentErrorContent = '';

            foreach ($errorContainer->childNodes as $childNode) {
                /* @var $childNode \DOMNode */
                if ($childNode->nodeType == XML_ELEMENT_NODE && $childNode->nodeName != 'span') {
                    $currentErrorContent .= $childNode->ownerDocument->saveHTML($childNode);
                }
            }

            $currentError = new \stdClass();
            $currentError->message = $currentErrorContent;
            $currentError->type = 'error';

            $outputObject->messages[] = $currentError;

            $messages[] = new ValidatorErrorMessage($currentErrorContent, 'validator-error');
        }

        return new MessageList($messages);
    }
}
