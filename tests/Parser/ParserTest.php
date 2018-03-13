<?php

namespace webignition\Tests\HtmlValidator\Parser;

use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Parser;
use webignition\Tests\HtmlValidator\Helper\FixtureLoader;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseInvalidOutputContentType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid content type: text/plain');
        $this->expectExceptionCode(1);

        $fixture = FixtureLoader::load('ValidatorOutput/invalid-output-content-type.txt');

        $parser = new Parser();
        $parser->parse($fixture);
    }

    /**
     * @dataProvider parseDataProvider
     *
     * @param string $fixtureName
     * @param array $expectedOutputMessages
     * @param bool $expectedOutputIsValid
     * @param bool $expectedOutputWasAborted
     */
    public function testParse(
        $fixtureName,
        array $expectedOutputMessages,
        $expectedOutputIsValid,
        $expectedOutputWasAborted
    ) {
        $fixture = FixtureLoader::load($fixtureName);

        $parser = new Parser();
        $output = $parser->parse($fixture);

        $this->assertInstanceOf(Output::class, $output);
        $this->assertEquals($expectedOutputMessages, $output->getMessages());
        $this->assertEquals($expectedOutputIsValid, $output->isValid());
        $this->assertEquals($expectedOutputWasAborted, $output->wasAborted());
    }

    /**
     * @return array
     */
    public function parseDataProvider()
    {
        return [
            'no errors' => [
                'fixtureName' => 'ValidatorOutput/0-errors.txt',
                'expectedMessages' => [],
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
            ],
            'three errors' => [
                'fixtureName' => 'ValidatorOutput/3-errors.txt',
                'expectedMessages' => [
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
                    (object)[
                        'lastLine' => 323,
                        'lastColumn' => 75,
                        'message' => '& did not start a character reference. '
                            .'(& probably should have been escaped as &amp;.)',
                        'explanation' => 'improper ampersand explanation',
                        'type' => 'error',
                        'messageid' => 'html5',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
            ],
            'validator internal connection timeout error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-connection-timeout.txt',
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-internal-connection-timeout.txt'),
                        'type' => 'error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
            ],
            'validator internal software error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-software-error.txt',
                'expectedMessages' => [
                    (object)[
                        'message' => 'Sorry, this document can\'t be checked',
                        'type' => 'error',
                        'messageId' => 'validator-internal-server-error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
            ],
            'validator invalid character encoding error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-character-encoding.txt',
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-invalid-character-encoding.txt'),
                        'type' => 'error',
                        'messageId' => 'character-encoding',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
            ],
            'validator invalid content type error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-content-type.txt',
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-invalid-content-type.txt'),
                        'type' => 'error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
            ],
        ];
    }
}
