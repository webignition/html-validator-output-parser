<?php

namespace webignition\Tests\HtmlValidator\Body;

use webignition\HtmlValidator\Output\Body\Parser;
use webignition\HtmlValidator\Output\Parser\Configuration;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    public function testGetConfigurationSetConfiguration()
    {
        $configuration = new Configuration();

        $this->assertNotEquals(spl_object_hash($configuration), spl_object_hash($this->parser->getConfiguration()));

        $this->parser->setConfiguration($configuration);
        $this->assertEquals(spl_object_hash($configuration), spl_object_hash($this->parser->getConfiguration()));
    }
}
