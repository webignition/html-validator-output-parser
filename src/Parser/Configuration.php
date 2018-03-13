<?php

namespace webignition\HtmlValidator\Output\Parser;

class Configuration
{
    /**
     * @var bool
     */
    private $ignoreAmpersandEncodingIssues = false;

    /**
     * @return Configuration
     */
    public function enableIgnoreAmpersandEncodingIssues()
    {
        $this->ignoreAmpersandEncodingIssues = true;

        return $this;
    }

    /**
     * @return Configuration
     */
    public function disableIgnoreAmpersandEncodingIssues()
    {
        $this->ignoreAmpersandEncodingIssues = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIgnoreAmpersandEncodingIssues()
    {
        return $this->ignoreAmpersandEncodingIssues;
    }
}
