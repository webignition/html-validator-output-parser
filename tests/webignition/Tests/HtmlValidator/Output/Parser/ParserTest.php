<?php

namespace webignition\Tests\HtmlValidator\Output\Parser;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser;

class ParserTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }    
    
    public function testParseReturnsOutput() {        
        $parser = new Parser();        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $parser->parse($this->getFixture('error-free.txt')));
    }
    
}