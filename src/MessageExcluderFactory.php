<?php

namespace webignition\HtmlValidatorOutput\Parser;

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
