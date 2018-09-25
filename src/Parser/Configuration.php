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

    public static function create(array $configurationValues = []): Configuration
    {
        $configuration = new Configuration();

        if (isset($configurationValues[self::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES])) {
            $configuration->enableIgnoreAmpersandEncodingIssues();
        }

        if (isset($configurationValues[self::KEY_CSS_VALIDATION_ISSUES])) {
            $configuration->enableIgnoreCssValidationIssues();
        }

        return $configuration;
    }

    public function enableIgnoreAmpersandEncodingIssues(): Configuration
    {
        $this->ignoreAmpersandEncodingIssues = true;

        return $this;
    }

    public function disableIgnoreAmpersandEncodingIssues(): Configuration
    {
        $this->ignoreAmpersandEncodingIssues = false;

        return $this;
    }

    public function getIgnoreAmpersandEncodingIssues(): bool
    {
        return $this->ignoreAmpersandEncodingIssues;
    }

    public function enableIgnoreCssValidationIssues()
    {
        $this->ignoreCssValidationIssues = true;
    }

    public function disableIgnoreCssValidationIssues()
    {
        $this->ignoreCssValidationIssues = false;
    }

    public function getIgnoreCssValidationIssues(): bool
    {
        return $this->ignoreCssValidationIssues;
    }
}
