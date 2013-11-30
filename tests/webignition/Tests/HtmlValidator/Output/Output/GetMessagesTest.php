<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class GetMessagesTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testErrorFreeResultReturnsEmptyMessageSet() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('0-errors.txt'));
        
        $this->assertEquals(array(), $output->getMessages());
    }    
    
    public function testResultWithThreeErrorsReturnsErrorSetWithThreeErrors() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('3-errors.txt'));
        
        $this->assertEquals(3, count($output->getMessages()));
    }    
    
    public function testParseValidatorInternalConnectionTimeoutReturnsErrorSetWithSingleError() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-connection-timeout-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }
    
    public function testParseValidatorInvalidContentTypeReturnsErrorSetWithSingleError() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-content-type-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }  
    
    public function testParseValidatorInternalSoftwareErrorReturnsErrorSetWithSingleError() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));       
    }    
    
    public function testParseValidatorInternalSoftwareErrorReturnsCorrectMessageId() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-internal-software-error.txt'));
        
        $messages = $output->getMessages();
        
        $this->assertEquals('validator-internal-server-error', $messages[0]->messageId);    
    }
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsErrorSetWithSingleError() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $this->assertEquals(1, count($output->getMessages()));
    }  
    
    public function testParseValidatorInvalidCharacterEncodingErrorReturnsCorrectMessageId() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('validator-invalid-character-encoding-error.txt'));
        
        $messages = $output->getMessages();
        
        $this->assertEquals('character-encoding', $messages[0]->messageId);    
    }    
    
}