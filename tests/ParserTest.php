<?php /** @noinspection PhpDocSignatureInspection */

namespace webignition\HtmlValidatorOutput\Parser\Tests;

use webignition\HtmlValidatorOutput\Models\Output;
use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\HtmlValidatorOutput\Models\ValidatorErrorMessage;
use webignition\HtmlValidatorOutput\Parser\Flags;
use webignition\HtmlValidatorOutput\Parser\InvalidContentTypeException;
use webignition\HtmlValidatorOutput\Parser\Parser;
use webignition\ValidatorMessage\MessageList;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParseUnparseableContentType()
    {
        $this->expectException(InvalidContentTypeException::class);
        $this->expectExceptionMessage('Invalid content type: t e x t');
        $this->expectExceptionCode(1);

        $fixture = FixtureLoader::load('ValidatorOutput/unparseable-output-content-type.txt');

        $parser = new Parser();
        $parser->parse($fixture);
    }


    public function testParseInvalidOutputContentType()
    {
        $this->expectException(InvalidContentTypeException::class);
        $this->expectExceptionMessage('Invalid content type: text/plain');
        $this->expectExceptionCode(1);

        $fixture = FixtureLoader::load('ValidatorOutput/invalid-output-content-type.txt');

        $parser = new Parser();
        $parser->parse($fixture);
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParseFoo(
        $fixtureName,
        int $flags,
        MessageList $expectedMessages,
        $expectedOutputIsValid,
        $expectedOutputWasAborted,
        $expectedErrorCount
    ) {
        $fixture = FixtureLoader::load($fixtureName);

        $parser = new Parser();
        $output = $parser->parse($fixture, $flags);

        $this->assertInstanceOf(Output::class, $output);
        $this->assertEquals(
            array_values($expectedMessages->getMessages()),
            array_values($output->getMessages()->getMessages())
        );
        $this->assertEquals($expectedOutputIsValid, $output->isValid());
        $this->assertEquals($expectedOutputWasAborted, $output->wasAborted());
        $this->assertEquals($expectedErrorCount, $output->getErrorCount());
    }

    public function parseDataProvider(): array
    {
        return [
            'no errors' => [
                'fixtureName' => 'ValidatorOutput/0-errors.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList(),
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
            'two errors' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidationErrorMessage(
                        'An img element must have an alt attribute, except under certain conditions. '
                        .'For details, consult guidance on providing text alternatives for images.',
                        'html5',
                        'image missing alt attribute explanation',
                        188,
                        79
                    ),
                    new ValidationErrorMessage(
                        '& did not start a character reference. '
                        .'(& probably should have been escaped as &amp;.)',
                        'html5',
                        'improper ampersand explanation',
                        282,
                        83
                    ),
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 2,
            ],
            'two errors, ignore ampersand encoding issues' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'flags' => Flags::IGNORE_AMPERSAND_ENCODING_ISSUES,
                'expectedMessages' => new MessageList([
                    new ValidationErrorMessage(
                        'An img element must have an alt attribute, except under certain conditions. '
                        .'For details, consult guidance on providing text alternatives for images.',
                        'html5',
                        'image missing alt attribute explanation',
                        188,
                        79
                    ),
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 1,
            ],
            'two errors, invalid info message json' => [
                'fixtureName' => 'ValidatorOutput/2-errors-invalid-info-message.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidationErrorMessage(
                        'An img element must have an alt attribute, except under certain conditions. '
                        .'For details, consult guidance on providing text alternatives for images.',
                        'html5',
                        'image missing alt attribute explanation',
                        188,
                        79
                    ),
                    new ValidationErrorMessage(
                        '& did not start a character reference. '
                        .'(& probably should have been escaped as &amp;.)',
                        'html5',
                        'improper ampersand explanation',
                        282,
                        83
                    ),
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 2,
            ],
            'validator internal connection timeout error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-connection-timeout.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-internal-connection-timeout.txt'),
                        'validator-error'
                    )
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'validator internal software error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-software-error.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        'Sorry, this document can\'t be checked',
                        'validator-internal-server-error'
                    )
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'validator invalid character encoding error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-character-encoding.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-invalid-character-encoding.txt'),
                        'character-encoding'
                    )
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'validator invalid content type error' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-content-type.txt',
                'flags' => Flags::NONE,
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-invalid-content-type.txt'),
                        'validator-error'
                    )
                ]),
                'expectedOutputIsValid' => false,
                'expectedOutputWasAborted' => true,
                'expectedErrorCount' => 1,
            ],
            'css errors only, ignore css validation issues' => [
                'fixtureName' => 'ValidatorOutput/css-errors-only.txt',
                'flags' => Flags::IGNORE_CSS_VALIDATION_ISSUES,
                'expectedMessages' => new MessageList(),
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
            'ampersand encoding issues only, ignore ampersand encoding issues' => [
                'fixtureName' => 'ValidatorOutput/ampersand-encoding-issues-only.txt',
                'flags' => Flags::IGNORE_AMPERSAND_ENCODING_ISSUES,
                'expectedMessages' => new MessageList(),
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
            'css errors and ampersand encoding issues only, ignore all' => [
                'fixtureName' => 'ValidatorOutput/css-errors-and-ampersand-encoding-errors-only.txt',
                'flags' => Flags::IGNORE_AMPERSAND_ENCODING_ISSUES | Flags::IGNORE_CSS_VALIDATION_ISSUES,
                'expectedMessages' => new MessageList(),
                'expectedOutputIsValid' => true,
                'expectedOutputWasAborted' => false,
                'expectedErrorCount' => 0,
            ],
        ];
    }
}
