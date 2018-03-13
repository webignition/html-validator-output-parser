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

    /**
     * @param string $htmlValidatorOutput
     *
     * @return Output
     */
    public function parse($htmlValidatorOutput)
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

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }
}
