<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\HtmlValidatorOutput\Parser\Tests;

use webignition\HtmlValidatorOutput\Models\AbstractIssueMessage;
use webignition\HtmlValidatorOutput\Models\InfoMessage;
use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\HtmlValidatorOutput\Parser\MessageFactory;

class MessageFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->messageFactory = new MessageFactory();
    }

    /**
     * @dataProvider createMessageFromArrayDataProvider
     */
    public function testCreateMessageFromArray(array $values, AbstractIssueMessage $expectedMessage)
    {
        $this->assertEquals($expectedMessage, $this->messageFactory->createMessageFromArray($values));
    }

    public function createMessageFromArrayDataProvider(): array
    {
        return [
            'error message' => [
                'values' => [
                    'lastLine' => 188,
                    'lastColumn' => 79,
                    'message' => 'An img element must have an alt attribute, except under certain conditions.',
                    'messageid' => 'html5',
                    'explanation' => 'image missing alt attribute explanation',
                    'type' => 'error',
                ],
                'expectedMessage' => new ValidationErrorMessage(
                    'An img element must have an alt attribute, except under certain conditions.',
                    'html5',
                    'image missing alt attribute explanation',
                    188,
                    79
                ),
            ],
            'info message' => [
                'values' => [
                    'message' => 'Info message message',
                    'messageid' => 'html5',
                    'explanation' => 'Info message explanation',
                    'type' => 'info',
                ],
                'expectedMessage' => new InfoMessage(
                    'Info message message',
                    'html5',
                    'Info message explanation'
                ),
            ],
        ];
    }
}
