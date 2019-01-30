<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\HtmlValidatorOutput\Parser\Tests;

use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\HtmlValidatorOutput\Parser\ApplicationJsonBodyParser;
use webignition\HtmlValidatorOutput\Parser\Configuration;
use webignition\ValidatorMessage\MessageList;

class ApplicationJsonParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($fixtureName, Configuration $configuration, MessageList $expectedMessages)
    {
        $parser = new ApplicationJsonBodyParser($configuration);
        $fixture = FixtureLoader::loadBodyContent($fixtureName);

        $messages = $parser->parse($fixture);

        $this->assertEquals(
            array_values($expectedMessages->getMessages()),
            array_values($messages->getMessages())
        );
    }

    public function parseDataProvider(): array
    {
        return [
            'not json' => [
                'fixtureName' => 'ValidatorOutput/not-array.txt',
                'configuration' => new Configuration(),
                'expectedMessages' => new MessageList(),
            ],
            'no messages key' => [
                'fixtureName' => 'ValidatorOutput/no-messages-key.txt',
                'configuration' => new Configuration(),
                'expectedMessages' => new MessageList(),
            ],
            'no errors' => [
                'fixtureName' => 'ValidatorOutput/0-errors.txt',
                'configuration' => new Configuration(),
                'expectedMessages' => new MessageList(),
            ],
            'two errors; no exclusions' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'configuration' => new Configuration(),
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
            ],
            'two errors; exclude ampersand issues' => [
                'fixtureName' => 'ValidatorOutput/2-errors.txt',
                'configuration' => new Configuration([
                    Configuration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                ]),
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
            ],
        ];
    }
}
