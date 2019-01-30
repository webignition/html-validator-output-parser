<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\ValidatorMessage\MessageList;

class BodyParser
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';
    const TEXT_HTML_CONTENT_TYPE = 'text/html';

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param HeaderValues $headerValues
     * @param string $content
     *
     * @return MessageList
     *
     * @throws InvalidContentTypeException
     */
    public function parse(HeaderValues $headerValues, string $content): MessageList
    {
        $contentType = $headerValues->getContentType();
        $contentTypeString = $contentType->getTypeSubtypeString();

        if ('application/json' === $contentTypeString) {
            $applicationJsonParser = new ApplicationJsonBodyParser($this->configuration);

            return $applicationJsonParser->parse($content);
        }

        if ('text/html' === $contentTypeString) {
            $textHtmlParser = new TextHtmlBodyParser();

            return $textHtmlParser->parse($content);
        }

        throw new InvalidContentTypeException($contentTypeString);
    }
}
