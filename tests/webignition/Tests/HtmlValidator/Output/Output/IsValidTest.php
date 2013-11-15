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
        $output = $parser->parse($this->getFixture('error-free.txt'));        
        $this->assertTrue($output->isValid());
    }
    
}