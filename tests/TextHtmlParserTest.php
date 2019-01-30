<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\HtmlValidatorOutput\Parser\Tests;

use webignition\HtmlValidatorOutput\Models\ValidatorErrorMessage;
use webignition\HtmlValidatorOutput\Parser\TextHtmlBodyParser;
use webignition\ValidatorMessage\MessageList;

class TextHtmlParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TextHtmlBodyParser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->parser = new TextHtmlBodyParser();
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($fixtureName, MessageList $expectedMessages)
    {
        $fixture = FixtureLoader::loadBodyContent($fixtureName);

        $messages = $this->parser->parse($fixture);

        $this->assertEquals(array_values($expectedMessages->getMessages()), array_values($messages->getMessages()));
    }

    public function parseDataProvider(): array
    {
        return [
            'validator internal connection timeout' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-connection-timeout.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-internal-connection-timeout.txt'),
                        'validator-error'
                    ),
                ]),
            ],
            'validator internal software error' => [
                'fixtureName' => 'ValidatorOutput/validator-internal-software-error.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        'Sorry, this document can\'t be checked',
                        'validator-internal-server-error'
                    ),
                ]),
            ],
            'validator invalid character encoding' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-character-encoding.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-invalid-character-encoding.txt'),
                        'character-encoding'
                    ),
                ]),
            ],
            'validator invalid content type' => [
                'fixtureName' => 'ValidatorOutput/validator-invalid-content-type.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage(
                        FixtureLoader::load('ExpectedMessage/validator-invalid-content-type.txt'),
                        'validator-error'
                    ),
                ]),
            ],
            'validator unknown error; empty html document' => [
                'fixtureName' => 'ValidatorOutput/validator-unknown-error.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage('An unknown error occurred', 'unknown'),
                ]),
            ],
            'validator unknown error; empty fatal errors' => [
                'fixtureName' => 'ValidatorOutput/validator-empty-fatal-errors.txt',
                'expectedMessages' => new MessageList([
                    new ValidatorErrorMessage('', 'validator-error'),
                ]),
            ],
        ];
    }
}
