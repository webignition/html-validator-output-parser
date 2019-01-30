<?php

namespace webignition\HtmlValidator\Output\Body;

use webignition\HtmlValidator\Output\Parser\Configuration;
use webignition\HtmlValidator\Output\Parser\HeaderValues;
use webignition\HtmlValidator\Output\Parser\InvalidContentTypeException;
use webignition\InternetMediaTypeInterface\InternetMediaTypeInterface;
use webignition\ValidatorMessage\MessageList;

class Parser
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

        if ($contentType instanceof InternetMediaTypeInterface) {
            $contentTypeString = $contentType->getTypeSubtypeString();

            if ('application/json' === $contentTypeString) {
                $applicationJsonParser = new ApplicationJsonParser($this->configuration);

                return $applicationJsonParser->parse($content);
            }

            if ('text/html' === $contentTypeString) {
                $textHtmlParser = new TextHtmlParser();

                return $textHtmlParser->parse($content);
            }

            throw new InvalidContentTypeException($contentTypeString);
        }

        throw new InvalidContentTypeException('');
    }
}
