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

    public function __construct(array $configurationValues = [])
    {
        $this->configuration = new Configuration($configurationValues);
    }

    public function parse(string $htmlValidatorOutput): Output
    {
        $headerBodyParts = HeaderBodySeparator::separate($htmlValidatorOutput);

        $headerParser = new HeaderParser();
        $header = $headerParser->parse($headerBodyParts[HeaderBodySeparator::PART_HEADER]);

        $bodyParser = new BodyParser($this->configuration);
        $body = $bodyParser->parse($header, $headerBodyParts[HeaderBodySeparator::PART_BODY]);

        if (empty($body->getMessages()) && Output::STATUS_INVALID === $header->get('status')) {
            $header->set('status', Output::STATUS_VALID);
        }

        $output = new Output($header, $body);

        return $output;
    }
}
