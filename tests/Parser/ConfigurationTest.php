<?php

namespace webignition\Tests\HtmlValidator\Parser;

use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Configuration;
use webignition\HtmlValidator\Output\Parser\Parser;
use webignition\Tests\HtmlValidator\Helper\FixtureLoader;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp(); //

        $this->configuration = new Configuration();
    }

    public function testIgnoreAmpersandEncodingIssues()
    {
        $this->assertFalse($this->configuration->getIgnoreAmpersandEncodingIssues());

        $this->configuration->enableIgnoreAmpersandEncodingIssues();
        $this->assertTrue($this->configuration->getIgnoreAmpersandEncodingIssues());

        $this->configuration->disableIgnoreAmpersandEncodingIssues();
        $this->assertFalse($this->configuration->getIgnoreAmpersandEncodingIssues());
    }
}
