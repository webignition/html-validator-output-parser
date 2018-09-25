<?php

namespace webignition\HtmlValidator\Output\Parser;

class Configuration
{
    /**
     * @var bool
     */
    private $ignoreAmpersandEncodingIssues = false;

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
}
