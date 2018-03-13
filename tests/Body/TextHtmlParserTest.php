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
        $fixture = FixtureLoader::loadBodyContent($fixtureName);

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
                'fixtureName' => 'ValidatorOutput/validator-internal-connection-timeout.txt',
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
                'fixtureName' => 'ValidatorOutput/validator-internal-software-error.txt',
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
                'fixtureName' => 'ValidatorOutput/validator-invalid-character-encoding.txt',
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
                'fixtureName' => 'ValidatorOutput/validator-invalid-content-type.txt',
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
                'fixtureName' => 'ValidatorOutput/validator-unknown-error.txt',
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
                'fixtureName' => 'ValidatorOutput/validator-empty-fatal-errors.txt',
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
