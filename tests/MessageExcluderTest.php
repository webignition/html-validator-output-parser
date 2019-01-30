<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\HtmlValidatorOutput\Parser\Tests;

use webignition\HtmlValidatorOutput\Models\ValidationErrorMessage;
use webignition\HtmlValidatorOutput\Parser\MessageExcluder;

class MessageExcluderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider isExcludedDataProvider
     */
    public function testIsExcluded(
        MessageExcluder $messageExcluder,
        ValidationErrorMessage $message,
        bool $expectedIsExcluded
    ) {
        $this->assertEquals($expectedIsExcluded, $messageExcluder->isExcluded($message));
    }

    public function isExcludedDataProvider(): array
    {
        return [
            'ampersand encoding issue; ignoreAmpersandEncodingIssues=false' => [
                'messageExcluder' => $this->createMessageExcluder(false, false),
                'message' => new ValidationErrorMessage(
                    '& did not start a character reference. '
                    .'(& probably should have been escaped as &amp;.)',
                    'html5',
                    '',
                    0,
                    0
                ),
                'expectedIsExcluded' => false,
            ],
            'ampersand encoding issue; ignoreAmpersandEncodingIssues=true' => [
                'messageExcluder' => $this->createMessageExcluder(true, false),
                'message' => new ValidationErrorMessage(
                    '& did not start a character reference. '
                    .'(& probably should have been escaped as &amp;.)',
                    'html5',
                    '',
                    0,
                    0
                ),
                'expectedIsExcluded' => true,
            ],
            'css validation issue; ignoreCssValidationIssues=false' => [
                'messageExcluder' => $this->createMessageExcluder(false, false),
                'message' => new ValidationErrorMessage(
                    'CSS: min-height: Too many values or values are not recognized.',
                    'html5',
                    '',
                    0,
                    0
                ),
                'expectedIsExcluded' => false,
            ],
            'css validation issue; ignoreCssValidationIssues=true' => [
                'messageExcluder' => $this->createMessageExcluder(false, true),
                'message' => new ValidationErrorMessage(
                    'CSS: min-height: Too many values or values are not recognized.',
                    'html5',
                    '',
                    0,
                    0
                ),
                'expectedIsExcluded' => true,
            ],
        ];
    }

    private function createMessageExcluder(
        bool $ignoreAmpersandEncodingIssues,
        bool $ignoreCssValidationIssues
    ): MessageExcluder {
        $messageExcluder = new MessageExcluder();
        $messageExcluder->setIgnoreAmpersandEncodingIssues($ignoreAmpersandEncodingIssues);
        $messageExcluder->setIgnoreCSSValidationIssues($ignoreCssValidationIssues);

        return $messageExcluder;
    }
}
