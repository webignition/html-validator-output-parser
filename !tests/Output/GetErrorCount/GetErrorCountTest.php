<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;

class GetErrorCountTest extends BaseTest
{
    public function setUp()
    {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        parent::setUp();
    }

    public function testErrorFreeResultHasErrorCountOfZero()
    {
        $output = $this->getParser()->parse($this->getFixture('0-errors.txt'));

        $this->assertEquals(0, $output->getErrorCount());
    }

    public function testResultWithThreeErrorsHasErrorCountOfThree()
    {
        $output = $this->getParser()->parse($this->getFixture('3-errors.txt'));

        $this->assertEquals(3, $output->getErrorCount());
    }

    public function testParseValidatorInternalConnectionTimeoutHasErrorCountOfOne()
    {
        $output = $this->getParser()->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));

        $this->assertEquals(1, $output->getErrorCount());
    }

    public function testParseValidatorInvalidContentTypeHasErrorCountOfOne()
    {
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-content-type-error.txt'));

        $this->assertEquals(1, $output->getErrorCount());
    }

    public function testParseValidatorInternalSoftwareErrorHasErrorCountOfNull()
    {
        $output = $this->getParser()->parse($this->getFixture('validator-internal-software-error.txt'));

        $this->assertNull($output->getErrorCount());
    }

    public function testParseValidatorInvalidCharacterEncodingHasErrorCountOfOne()
    {
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));

        $this->assertEquals(1, $output->getErrorCount());
    }
}
