<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser\Parser;

class GetErrorCountTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testErrorFreeResultHasErrorCountOfZero() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('0-errors.txt'));
        
        $this->assertEquals(0, $output->getErrorCount());
    }    
    
    public function testResultWithThreeErrorsHasErrorCountOfThree() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('3-errors.txt'));
        
        $this->assertEquals(3, $output->getErrorCount());
    }    
    
    public function testParseValidatorInternalConnectionTimeoutHasErrorCountOfOne() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertEquals(1, $output->getErrorCount());       
    }
    
    public function testParseValidatorInvalidContentTypeHasErrorCountOfOne() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertEquals(1, $output->getErrorCount());      
    }    
    
    public function testParseValidatorInternalSoftwareErrorHasErrorCountOfNull() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $this->assertNull( $output->getErrorCount());      
    }    
    
    public function testParseValidatorInvalidCharacterEncodingHasErrorCountOfOne() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $this->assertEquals(1, $output->getErrorCount());       
    }    
}