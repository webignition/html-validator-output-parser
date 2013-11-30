<?php

namespace webignition\Tests\HtmlValidator\Output\Parser;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class ParserTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseExpectedValidatorOutputReturnsOutputObject() {        
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('0-errors.txt')));
    }
    
    public function testParseValiatorInternalConnectionTimeoutFailureReturnsOutputObject() {        
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('validator-internal-connection-timeout-error.txt')));
    }    
    
    public function testParseValiatorInvalidContentTypeFailureReturnsOutputObject() {        
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('validator-invalid-content-type-error.txt')));
    } 
    
    public function testParseValidatorInternalSoftwareErrorReturnsOutputObject() {
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('validator-internal-software-error.txt')));        
    }
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsOutputObject() {
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('validator-invalid-character-encoding-error.txt')));        
    }
    
    public function testParseResponseWithCarriageReturnLineFeedSeparatorsReturnsOutputObject() {
        $validatorOutput = 'HTTP/1.1 500' . "\r\n"
                . 'Content-Type:text/html'  . "\r\n\r\n"
                . '<h1>Software error:</h1>' . "\r\n"
                . '<p>' . "\r\n"
                . 'error wording' . "\r\n"
                . '</p>';
        
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($validatorOutput));
    }    
    
}