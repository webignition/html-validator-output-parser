<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser\Parser;

class GetMessagesTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        parent::setUp();
    }    
    
    public function testErrorFreeResultReturnsEmptyMessageSet() {        
        $output = $this->getParser()->parse($this->getFixture('0-errors.txt'));
        
        $this->assertEquals(array(), $output->getMessages());
    }    
    
    public function testResultWithThreeErrorsReturnsErrorSetWithThreeErrors() {        
        $output = $this->getParser()->parse($this->getFixture('3-errors.txt'));
        
        $this->assertEquals(3, count($output->getMessages()));
    }    
    
    public function testParseValidatorInternalConnectionTimeoutReturnsErrorSetWithSingleError() {        
        $output = $this->getParser()->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }
    
    public function testParseValidatorInvalidContentTypeReturnsErrorSetWithSingleError() {        
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }  
    
    public function testParseValidatorInternalSoftwareErrorReturnsErrorSetWithSingleError() {        
        $output = $this->getParser()->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }    
    
    public function testParseValidatorInternalSoftwareErrorReturnsCorrectMessageId() {        
        $output = $this->getParser()->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $messages = $output->getMessages();
        
        $this->assertEquals('validator-internal-server-error', $messages[0]->messageId);    
    }
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsErrorSetWithSingleError() {        
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));
    }  
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsCorrectMessageId() {        
        $output = $this->getParser()->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $messages = $output->getMessages();
        
        $this->assertEquals('character-encoding', $messages[0]->messageId);    
    }    
    
}