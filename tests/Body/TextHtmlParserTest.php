<?php

namespace webignition\Tests\HtmlValidator\Body;

use webignition\HtmlValidator\Output\Body\TextHtmlParser;
use webignition\Tests\HtmlValidator\Helper\FixtureLoader;

class TextHtmlParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TextHtmlParser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new TextHtmlParser();
    }

    /**
     * @dataProvider parseDataProvider
     *
     * @param $fixtureName
     * @param \stdClass $expectedParserOutput
     */
    public function testParse($fixtureName, \stdClass $expectedParserOutput)
    {
        $fixture = FixtureLoader::load($fixtureName);

        $parserOutput = $this->parser->parse($fixture);
        $this->assertEquals($expectedParserOutput, $parserOutput);
    }

    /**
     * @return array
     */
    public function parseDataProvider()
    {
        return [
            'validator internal connection timeout' => [
                'fixtureName' => 'ValidatorBodyContent/internal-connection-timeout.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => FixtureLoader::load(
                                'ExpectedMessage/validator-internal-connection-timeout.txt'
                            ),
                            'type' => 'error',
                        ],
                    ],
                ],
            ],
            'validator internal software error' => [
                'fixtureName' => 'ValidatorBodyContent/internal-software-error.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => 'Sorry, this document can\'t be checked',
                            'type' => 'error',
                            'messageId' => 'validator-internal-server-error',
                        ],
                    ],
                ],
            ],
            'validator invalid character encoding' => [
                'fixtureName' => 'ValidatorBodyContent/invalid-character-encoding.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => FixtureLoader::load(
                                'ExpectedMessage/validator-invalid-character-encoding.txt'
                            ),
                            'type' => 'error',
                            'messageId' => 'character-encoding',
                        ],
                    ],
                ],
            ],
            'validator invalid content type' => [
                'fixtureName' => 'ValidatorBodyContent/invalid-content-type.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => FixtureLoader::load('ExpectedMessage/validator-invalid-content-type.txt'),
                            'type' => 'error',
                        ],
                    ],
                ],
            ],
            'validator unknown error; empty html document' => [
                'fixtureName' => 'ValidatorBodyContent/unknown-error.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => 'An unknown error occurred',
                            'type' => 'error',
                            'messageId' => 'unknown',
                        ],
                    ],
                ],
            ],
            'validator unknown error; empty fatal errors' => [
                'fixtureName' => 'ValidatorBodyContent/empty-fatal-error.html',
                'expectedParserOutput' => (object)[
                    'messages' => [
                        (object)[
                            'message' => '',
                            'type' => 'error',
                        ],
                    ],
                ],
            ],
        ];
    }
}
