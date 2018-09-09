<?php

namespace webignition\HtmlValidator\Output\Body;

use webignition\HtmlValidator\Output\Parser\Configuration;

class ApplicationJsonParser
{
    const AMPERSAND_ENCODING_MESSAGE =
        '& did not start a character reference. (& probably should have been escaped as &amp;.)';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
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

    /**
     * @param string $htmlValidatorBodyContent
     *
     * @return \stdClass
     */
    public function parse($htmlValidatorBodyContent)
    {
        $htmlValidatorBodyContent = $this->fixInvalidJson($htmlValidatorBodyContent);

        $parsedBody = json_decode($htmlValidatorBodyContent);
        $messages = $parsedBody->messages;

        foreach ($messages as $index => $message) {
            if ($this->isMessageToBeExcluded($message)) {
                unset($messages[$index]);
            }
        }

        $parsedBody->messages = $messages;

        return $parsedBody;
    }

    /**
     * @param \stdClass $message
     *
     * @return bool
     */
    private function isMessageToBeExcluded($message)
    {
        $configuration = $this->getConfiguration();
        $ignoreAmpersandEncodingIssues = $configuration->getIgnoreAmpersandEncodingIssues();

        if (self::AMPERSAND_ENCODING_MESSAGE === $message->message && $ignoreAmpersandEncodingIssues) {
            return true;
        }

        return false;
    }

    private function fixInvalidJson($htmlValidatorBodyContent)
    {
        $emptyMessage = '"message": ,';
        $nonEmptyMessage = '"message": "",';

        if (substr_count($htmlValidatorBodyContent, $emptyMessage)) {
            $htmlValidatorBodyContent = str_replace($emptyMessage, $nonEmptyMessage, $htmlValidatorBodyContent);
        }

        return $htmlValidatorBodyContent;
    }
}
