<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class IsValidTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testErrorFreeResultIsValid() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('0-errors.txt'));        
        $this->assertTrue($output->isValid());
    }  

    public function testResultWithThreeErrorsIsNotValid() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('3-errors.txt'));
        
        $this->assertFalse($output->isValid());
    }    
    
    public function testValidatorInternalConnectionTimeoutIsNeitherValidNorInvalid() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertNull($output->isValid());        
    }      
    
    public function testValidatorInvalidContentTypeIsNeitherValidNorInvalid() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertNull($output->isValid());        
    }      
    
}