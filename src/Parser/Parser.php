<?php

namespace webignition\HtmlValidator\Output\Parser;

use webignition\HtmlValidator\Output\Body\BodyParser as BodyParser;
use webignition\HtmlValidatorOutput\Models\Output;
use webignition\InternetMediaType\Parameter\Parser\AttributeParserException;
use webignition\InternetMediaType\Parser\Parser as ContentTypeParser;
use webignition\InternetMediaType\Parser\SubtypeParserException;
use webignition\InternetMediaType\Parser\TypeParserException;

class Parser
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(array $configurationValues = [])
    {
        $this->configure($configurationValues);
    }

    public function configure(array $configurationValues)
    {
        $this->configuration = new Configuration($configurationValues);
    }

    public function parse(string $htmlValidatorOutput): Output
    {
        $headerBodyParts = HeaderBodySeparator::separate($htmlValidatorOutput);

        $headerValues = $this->parseHeaderValues($headerBodyParts['header']);

        $bodyParser = new BodyParser($this->configuration);
        $messages = $bodyParser->parse($headerValues, $headerBodyParts[HeaderBodySeparator::PART_BODY]);

        $output = new Output($messages);

        if ($headerValues->getWasAborted()) {
            $output->setWasAborted(true);
        }

        return $output;
    }

    /**
     * @param string $header
     *
     * @return HeaderValues
     *
     * @throws InvalidContentTypeException
     */
    public function parseHeaderValues(string $header): HeaderValues
    {
        $headerLines = explode("\n", $header);

        $contentType = null;
        $wasAborted = true;

        foreach ($headerLines as $headerLine) {
            $keyValueParts = explode(':', $headerLine, 2);
            $key = strtolower($keyValueParts[0]);
            $value = $keyValueParts[1];

            if ('content-type' === $key) {
                $contentTypeString = trim($value);
                $contentTypeParser = new ContentTypeParser();

                try {
                    $contentType = $contentTypeParser->parse($contentTypeString);
                } catch (AttributeParserException $e) {
                } catch (SubtypeParserException $e) {
                } catch (TypeParserException $e) {
                }

                if (null === $contentType) {
                    throw new InvalidContentTypeException($contentTypeString);
                }
            }

            if ('x-w3c-validator-status' === $key) {
                $status = strtolower(trim($value));

                if ('valid' === $status || 'invalid' === $status) {
                    $wasAborted = false;
                }
            }
        }

        return new HeaderValues($wasAborted, $contentType);
    }
}
