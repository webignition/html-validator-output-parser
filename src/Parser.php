<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\HtmlValidatorOutput\Models\Output;
use webignition\InternetMediaType\Parameter\Parser\AttributeParserException;
use webignition\InternetMediaType\Parser\Parser as ContentTypeParser;
use webignition\InternetMediaType\Parser\SubtypeParserException;
use webignition\InternetMediaType\Parser\TypeParserException;
use webignition\InternetMediaTypeInterface\InternetMediaTypeInterface;
use webignition\ValidatorMessage\MessageList;

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
        $messages = $this->parseBody($headerValues->getContentType(), $headerBodyParts[HeaderBodySeparator::PART_BODY]);

        $messageExcluder = MessageExcluderFactory::create($this->configuration);
        $messages = $messageExcluder->filter($messages);

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
    private function parseHeaderValues(string $header): HeaderValues
    {
        $headerLines = explode("\n", $header);

        $contentType = null;
        $wasAborted = true;
        $contentTypeString = '';

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
            }

            if ('x-w3c-validator-status' === $key) {
                $status = strtolower(trim($value));

                if ('valid' === $status || 'invalid' === $status) {
                    $wasAborted = false;
                }
            }
        }

        if ($contentType instanceof InternetMediaTypeInterface) {
            return new HeaderValues($wasAborted, $contentType);
        }

        throw new InvalidContentTypeException($contentTypeString);
    }

    /**
     * @param InternetMediaTypeInterface $contentType
     * @param string $body
     *
     * @return MessageList
     *
     * @throws InvalidContentTypeException
     */
    private function parseBody(InternetMediaTypeInterface $contentType, string $body): MessageList
    {
        $contentTypeString = $contentType->getTypeSubtypeString();

        if ('application/json' === $contentTypeString) {
            $applicationJsonParser = new ApplicationJsonBodyParser();

            return $applicationJsonParser->parse($body);
        }

        if ('text/html' === $contentTypeString) {
            $textHtmlParser = new TextHtmlBodyParser();

            return $textHtmlParser->parse($body);
        }

        throw new InvalidContentTypeException($contentTypeString);
    }
}
