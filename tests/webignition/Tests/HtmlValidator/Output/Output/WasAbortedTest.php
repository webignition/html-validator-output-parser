<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class WasAbortedTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testErrorFreeResultWasNotAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('0-errors.txt'));
        
        $this->assertFalse($output->wasAborted());
    }    
    
    public function testResultWithThreeErrorsWasNotAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('3-errors.txt'));
        
        $this->assertFalse($output->wasAborted());
    }    
    
    public function testParseValidatorInternalConnectionTimeoutWasAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertTrue($output->wasAborted());      
    }
    
    public function testParseValidatorInvalidContentTypeWasAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertTrue($output->wasAborted());  
    }    
    
    public function testParseValidatorInternalSoftwareErrorWasAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $this->assertTrue($output->wasAborted());
    }    
    
    public function testParseValidatorInvalidCharacterEncodingWasAborted() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $this->assertTrue($output->wasAborted());    
    }    
}