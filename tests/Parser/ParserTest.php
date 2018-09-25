<?php

namespace webignition\Tests\HtmlValidator\Parser;

use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Configuration;
use webignition\HtmlValidator\Output\Parser\Parser;
use webignition\Tests\HtmlValidator\Helper\FixtureLoader;

class ParserTest extends \PHPUnit\Framework\TestCase
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
     * @param array $configurationValues
     * @param array $expectedOutputMessages
     * @param bool $expectedOutputIsValid
     * @param bool $expectedOutputWasAborted
     * @param int $expectedErrorCount
     */
    public function testParse(
        $fixtureName,
        array $configurationValues,
        array $expectedOutputMessages,
        $expectedOutputIsValid,
        $expectedOutputWasAborted,
        $expectedErrorCount
    ) {
        $fixture = FixtureLoader::load($fixtureName);

        $parser = new Parser($configurationValues);
        $output = $parser->parse($fixture);

        $this->assertInstanceOf(Output::class, $output);
        $this->assertEquals($expectedOutputMessages, $output->getMessages());
        $this->assertEquals($expectedOutputIsValid, $output->isValid());
        $this->assertEquals($expectedOutputWasAborted, $output->wasAborted());
        $this->assertEquals($expectedErrorCount, $output->getErrorCount());
    }

    public function parseDataProvider(): array
    {
        return [
            'no errors' => [
                'fixtureName' => 'ValidatorOutput/0-errors.txt',
                'configurationValues' => [],
                'expectedMessages' => [],
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
            'two errors' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'configurationValues' => [],
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
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 2,
            ],
            'two errors, ignore ampersand encoding issues' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'configurationValues' => [
                    Configuration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                ],
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
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 1,
            ],
            'two errors, invalid info message json' => [
                'fixtureName' => 'ValidatorOutput/2-errors-invalid-info-message.txt',
                'configurationValues' => [],
                'expectedMessages' => [
                    (object)[
                        'message' => '',
                        'explanation' => 'foo',
                        'type' => 'info',
                        'messageid' => 'html5',
                    ],
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
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 2,
            ],
            'validator internal connection timeout error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-connection-timeout.txt',
                'configurationValues' => [],
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-internal-connection-timeout.txt'),
                        'type' => 'error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'validator internal software error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-software-error.txt',
                'configurationValues' => [],
                'expectedMessages' => [
                    (object)[
                        'message' => 'Sorry, this document can\'t be checked',
                        'type' => 'error',
                        'messageId' => 'validator-internal-server-error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => null,
            ],
            'validator invalid character encoding error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-character-encoding.txt',
                'configurationValues' => [],
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-invalid-character-encoding.txt'),
                        'type' => 'error',
                        'messageId' => 'character-encoding',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'validator invalid content type error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-content-type.txt',
                'configurationValues' => [],
                'expectedMessages' => [
                    (object)[
                        'message' => FixtureLoader::load('ExpectedMessage/validator-invalid-content-type.txt'),
                        'type' => 'error',
                    ],
                ],
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'css errors only, ignore css validation issues' => [
                'fixtureName' => 'ValidatorOutput/css-errors-only.txt',
                'configurationValues' => [
                    Configuration::KEY_CSS_VALIDATION_ISSUES => true,
                ],
                'expectedMessages' => [],
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
            'ampersand encoding issues only, ignore ampersand encoding issues' => [
                'fixtureName' => 'ValidatorOutput/ampersand-encoding-issues-only.txt',
                'configurationValues' => [
                    Configuration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                ],
                'expectedMessages' => [],
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
        ];
    }
}
