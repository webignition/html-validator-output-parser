<?php

namespace webignition\HtmlValidator\Output\Body;

use webignition\HtmlValidator\Output\Parser\Configuration;
use webignition\HtmlValidator\Output\Header\Header;

class Parser
{
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';
    const TEXT_HTML_CONTENT_TYPE = 'text/html';

    /**
     * @var Configuration
     */
    private $configuration;

    public function parse(Header $header, string $htmlValidatorBodyContent): Body
    {
        $body = new Body();

        switch ($header->get('content-type')->getTypeSubtypeString()) {
            case 'application/json':
                $applicationJsonParser = new ApplicationJsonParser();
                $applicationJsonParser->setConfiguration($this->getConfiguration());

                $body->setContent($applicationJsonParser->parse($htmlValidatorBodyContent));
                break;

            case 'text/html':
                $textHtmlParser = new TextHtmlParser();
                $body->setContent($textHtmlParser->parse($htmlValidatorBodyContent));
                break;

            default:
                throw new \InvalidArgumentException(
                    'Invalid content type: ' . $header->get('content-type')->getTypeSubtypeString(),
                    1
                );
        }

        return $body;
    }

    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): Configuration
    {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }
}
