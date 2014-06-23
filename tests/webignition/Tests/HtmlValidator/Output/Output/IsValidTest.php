<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser\Parser;

class IsValidTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        parent::setUp();
    }    
    
    public function testErrorFreeResultIsValid() {        
        $output = $this->getParser()->parse($this->getFixture('0-errors.txt'));
        $this->assertTrue($output->isValid());
    }  

    public function testResultWithThreeErrorsIsNotValid() {        
        $output = $this->getParser()->parse($this->getFixture('3-errors.txt'));
        
        $this->assertFalse($output->isValid());
    }    
    
    public function testValidatorInternalConnectionTimeoutIsNeitherValidNorInvalid() {        
        $output = $this->getParser()->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertNull($output->isValid());        
    }      
    
    public function testValidatorInvalidContentTypeIsNeitherValidNorInvalid() {        
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertNull($output->isValid());        
    }     
    
    public function testParseValidatorInternalSoftwareErrorIsNeitherValidNorInvalid() {        
        $output = $this->getParser()->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $this->assertNull($output->isValid());    
    }      
    
    
    public function testParseValidatorInvalidCharacterEncodingErrorIsNeitherValidInvalid() {        
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $this->assertNull($output->isValid());    
    }         
    
}