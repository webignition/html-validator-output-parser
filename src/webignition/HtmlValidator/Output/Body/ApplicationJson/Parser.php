<?php

namespace webignition\HtmlValidator\Output\Body\ApplicationJson;

use webignition\HtmlValidator\Output\Parser\Configuration;

class Parser {

    const AMPERSAND_ENCODING_MESSAGE = '& did not start a character reference. (& probably should have been escaped as &amp;.)';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     *
     * @var string
     */
    private $htmlValidatorBodyContent = null;


    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
    }


    /**
     * @return Configuration
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }


    public function parse($htmlValidatorBodyContent) {
        $this->htmlValidatorBodyContent = $htmlValidatorBodyContent;

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


    private function isMessageToBeExcluded($message) {
        if ($message->message === self::AMPERSAND_ENCODING_MESSAGE && $this->getConfiguration()->getIgnoreAmpersandEncodingIssues()) {
            return true;
        }

        return false;
    }


}