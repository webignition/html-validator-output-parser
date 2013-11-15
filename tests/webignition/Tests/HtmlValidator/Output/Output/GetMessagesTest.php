<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class OutputTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testErrorFreeResultReturnsEmptyMessageSet() {        
        $parser = new Parser();
        $output = $parser->parse($this->getFixture('error-free.txt'));
        
        $this->assertEquals(array(), $output->getMessages());
    }
    
}