<?php

namespace webignition\HtmlValidator\Output\Body;

class TextHtmlParser
{
    const SOFTWARE_ERROR_HEADING_CONTENT = 'Software error:';

    /**
     * @var string
     */
    private $htmlValidatorBodyContent = null;

    /**
     * @var \DOMDocument
     */
    private $dom = null;

    /**
     * @param string $htmlValidatorBodyContent
     *
     * @return null|\stdClass
     */
    public function parse(string $htmlValidatorBodyContent)
    {
        $this->htmlValidatorBodyContent = $htmlValidatorBodyContent;

        if ($this->isValidatorSoftwareError()) {
            return $this->getValidatorInternalErrorOutputObject();
        }

        if ($this->isCharacterEncodingError()) {
            return $this->getCharacterEncodingErrorOutputObject();
        }

        if ($this->hasFatalErrorsCollection()) {
            return $this->getFatalErrorCollectionOutputObject();
        }

        return $this->getValidatorUnknownErrorOutputObject();
    }

    private function getDom(): \DOMDocument
    {
        if (is_null($this->dom)) {
            $this->dom = new \DOMDocument();
            $this->dom->loadHTML($this->htmlValidatorBodyContent);
        }

        return $this->dom;
    }

    private function hasFatalErrorsCollection(): bool
    {
        return !is_null($this->getFatalErrorsCollection());
    }

    /**
     * @return \DOMElement|null
     */
    private function getFatalErrorsCollection()
    {
        return $this->getDom()->getElementById('fatal-errors');
    }

    private function isValidatorSoftwareError(): bool
    {
        $levelOneHeading = $this->getLevelOneHeading();

        if (empty($levelOneHeading)) {
            return false;
        }

        return $this->getLevelOneHeading()->textContent === self::SOFTWARE_ERROR_HEADING_CONTENT;
    }

    /**
     * @return \DOMElement|null
     */
    private function getLevelOneHeading()
    {
        /* @var \DOMNodeList */
        $levelOneHeadings = $this->getDom()->getElementsByTagName('h1');

        if (0 === $levelOneHeadings->length) {
            return null;
        }

        return $levelOneHeadings->item(0);
    }

    private function isCharacterEncodingError(): bool
    {
        if (!$this->hasFatalErrorsCollection()) {
            return false;
        }

        $fatalErrorCollectionOutputObject = $this->getFatalErrorCollectionOutputObject();

        $messages = $fatalErrorCollectionOutputObject->messages;

        $message = $messages[0];

        return substr_count($message->message, 'contained one or more bytes that I cannot interpret') === 1;
    }

    private function getValidatorInternalErrorOutputObject(): \stdClass
    {
        $outputObject = new \stdClass();
        $outputObject->messages = array();

        $currentError = new \stdClass();
        $currentError->message = 'Sorry, this document can\'t be checked';
        $currentError->type = 'error';
        $currentError->messageId = 'validator-internal-server-error';

        $outputObject->messages[] = $currentError;
        return $outputObject;
    }

    private function getCharacterEncodingErrorOutputObject(): \stdClass
    {
        $fatalErrorCollectionOutputObject = $this->getFatalErrorCollectionOutputObject();
        $message = $fatalErrorCollectionOutputObject->messages[0]->message;

        $outputObject = new \stdClass();
        $outputObject->messages = array();

        $currentError = new \stdClass();
        $currentError->message = $message;
        $currentError->type = 'error';
        $currentError->messageId = 'character-encoding';

        $outputObject->messages[] = $currentError;

        return $outputObject;
    }

    private function getValidatorUnknownErrorOutputObject(): \stdClass
    {
        $outputObject = new \stdClass();
        $outputObject->messages = array();

        $currentError = new \stdClass();
        $currentError->message = 'An unknown error occurred';
        $currentError->type = 'error';
        $currentError->messageId = 'unknown';

        $outputObject->messages[] = $currentError;

        return $outputObject;
    }

    private function getFatalErrorCollectionOutputObject(): \stdClass
    {
        $errorContainers = $this->getFatalErrorsCollection()->getElementsByTagName('li');

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
        }

        return $outputObject;
    }
}
