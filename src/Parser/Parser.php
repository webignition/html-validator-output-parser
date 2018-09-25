<?php

namespace webignition\HtmlValidator\Output\Parser;

use webignition\HtmlValidator\Output\Header\Parser as HeaderParser;
use webignition\HtmlValidator\Output\Body\Parser as BodyParser;
use webignition\HtmlValidator\Output\Output;

class Parser
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function parse(string $htmlValidatorOutput): Output
    {
        $headerBodyParts = HeaderBodySeparator::separate($htmlValidatorOutput);

        $headerParser = new HeaderParser();
        $header = $headerParser->parse($headerBodyParts[HeaderBodySeparator::PART_HEADER]);

        $bodyParser = new BodyParser();
        $bodyParser->setConfiguration($this->getConfiguration());
        $body = $bodyParser->parse($header, $headerBodyParts[HeaderBodySeparator::PART_BODY]);

        $output = new Output($header, $body);

        return $output;
    }

    public function getConfiguration(): Configuration
    {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }
}
