<?php

namespace webignition\Tests\HtmlValidator\Output\Parser;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser\Parser;

class ParserTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        parent::setUp();
    }    
    
    public function testParseExpectedValidatorOutputReturnsOutputObject() {

        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($this->getFixture('0-errors.txt')));
    }
    
    public function testParseValiatorInternalConnectionTimeoutFailureReturnsOutputObject() {

        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($this->getFixture('validator-internal-connection-timeout-error.txt')));
    }    
    
    public function testParseValiatorInvalidContentTypeFailureReturnsOutputObject() {
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($this->getFixture('validator-invalid-content-type-error.txt')));
    } 
    
    public function testParseValidatorInternalSoftwareErrorReturnsOutputObject() {
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($this->getFixture('validator-internal-software-error.txt')));
    }
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsOutputObject() {
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($this->getFixture('validator-invalid-character-encoding-error.txt')));
    }
    
    public function testParseResponseWithCarriageReturnLineFeedSeparatorsReturnsOutputObject() {
        $validatorOutput = 'HTTP/1.1 500' . "\r\n"
                . 'Content-Type:text/html'  . "\r\n\r\n"
                . '<h1>Software error:</h1>' . "\r\n"
                . '<p>' . "\r\n"
                . 'error wording' . "\r\n"
                . '</p>';

        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $this->getParser()->parse($validatorOutput));
    }    
    
}