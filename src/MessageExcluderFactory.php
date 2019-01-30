<?php

namespace webignition\HtmlValidatorOutput\Parser\MessageExcluder;

use webignition\HtmlValidator\Output\Parser\Configuration;

class MessageExcluderFactory
{
    public static function create($configuration = null)
    {
        $messageExcluder = new MessageExcluder();

        if ($configuration instanceof Configuration) {
            if ($configuration->getIgnoreAmpersandEncodingIssues()) {
                $messageExcluder->setIgnoreAmpersandEncodingIssues(true);
            }

            if ($configuration->getIgnoreCssValidationIssues()) {
                $messageExcluder->setIgnoreCSSValidationIssues(true);
            }
        }

        return $messageExcluder;
    }
}
