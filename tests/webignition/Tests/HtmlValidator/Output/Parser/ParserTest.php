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
    
}