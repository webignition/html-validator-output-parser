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
    
}