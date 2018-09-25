<?php

namespace webignition\HtmlValidator\Output\Parser;

class Configuration
{
    const KEY_IGNORE_AMPERSAND_ENCODING_ISSUES = 'ignore-ampersand-encoding-issues';
    const KEY_CSS_VALIDATION_ISSUES = 'ignore-css-validation-issues';

    /**
     * @var bool
     */
    private $ignoreAmpersandEncodingIssues = false;

    /**
     * @var bool
     */
    private $ignoreCssValidationIssues = false;

    public function __construct(array $configurationValues = [])
    {
        if (isset($configurationValues[self::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES])) {
            $this->ignoreAmpersandEncodingIssues = true;
        }

        if (isset($configurationValues[self::KEY_CSS_VALIDATION_ISSUES])) {
            $this->ignoreCssValidationIssues = true;
        }
    }

    public function getIgnoreAmpersandEncodingIssues(): bool
    {
        return $this->ignoreAmpersandEncodingIssues;
    }

    public function getIgnoreCssValidationIssues(): bool
    {
        return $this->ignoreCssValidationIssues;
    }
}
