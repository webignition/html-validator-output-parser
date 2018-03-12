<?php

namespace webignition\Tests\HtmlValidator\Body\ApplicationJson;

use webignition\HtmlValidator\Output\Body\ApplicationJson\Parser;
use webignition\HtmlValidator\Output\Parser\Configuration;
use webignition\Tests\HtmlValidator\Helper\FixtureLoader;

class ParserTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @dataProvider parseDataProvider
     *
     * @param $fixtureName
     * @param Configuration $configuration
     * @param \stdClass $expectedParserOutput
     */
    public function testParse($fixtureName, Configuration $configuration, \stdClass $expectedParserOutput)
    {
        $fixture = FixtureLoader::load($fixtureName);
        $this->parser->setConfiguration($configuration);

        $parserOutput = $this->parser->parse($fixture);

        $this->assertEquals($expectedParserOutput, $parserOutput);
    }

    /**
     * @return array
     */
    public function parseDataProvider()
    {
        $ignoreAmpersandEncodingIssuesConfiguration = new Configuration();
        $ignoreAmpersandEncodingIssuesConfiguration->enableIgnoreAmpersandEncodingIssues();

        return [
            'no errors' => [
                'fixtureName' => 'ValidatorBodyContent/0-errors.json',
                'configuration' => new Configuration(),
                'expectedParserOutput' => (object)[
                    'url' => 'upload://Form Submission',
                    'messages' => [],
                    'source' => (object)[
                        'encoding' => 'utf-8',
                        'type' => '',
                    ],
                ],
            ],
            'two errors; no exclusions' => [
                'fixtureName' => 'ValidatorBodyContent/2-errors.json',
                'configuration' => new Configuration(),
                'expectedParserOutput' => (object)[
                    'url' => 'http://blog.simplytestable.com/',
                    'messages' => [
                        (object)[
                            'lastLine' => 188,
                            'lastColumn' => 79,
                            'message' => 'An img element must have an alt attribute, except under certain conditions. '
                                .'For details, consult guidance on providing text alternatives for images.',
                            'explanation' => 'image missing alt attribute explanation',
                            'type' => 'error',
                            'messageid' => 'html5',
                        ],
                        (object)[
                            'lastLine' => 282,
                            'lastColumn' => 83,
                            'message' => '& did not start a character reference. '
                                .'(& probably should have been escaped as &amp;.)',
                            'explanation' => 'improper ampersand explanation',
                            'type' => 'error',
                            'messageid' => 'html5',
                        ],
                    ],
                    'source' => (object)[
                        'encoding' => 'utf-8',
                        'type' => 'text/html',
                    ],
                ],
            ],
            'two errors; exclude ampersand issues' => [
                'fixtureName' => 'ValidatorBodyContent/2-errors.json',
                'configuration' => $ignoreAmpersandEncodingIssuesConfiguration,
                'expectedParserOutput' => (object)[
                    'url' => 'http://blog.simplytestable.com/',
                    'messages' => [
                        (object)[
                            'lastLine' => 188,
                            'lastColumn' => 79,
                            'message' => 'An img element must have an alt attribute, except under certain conditions. '
                                .'For details, consult guidance on providing text alternatives for images.',
                            'explanation' => 'image missing alt attribute explanation',
                            'type' => 'error',
                            'messageid' => 'html5',
                        ],
                    ],
                    'source' => (object)[
                        'encoding' => 'utf-8',
                        'type' => 'text/html',
                    ],
                ],
            ],
        ];
    }
}
