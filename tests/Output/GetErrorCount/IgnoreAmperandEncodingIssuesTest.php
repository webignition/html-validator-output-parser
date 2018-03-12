<?php

namespace webignition\Tests\HtmlValidator\Output\Output;

use webignition\Tests\HtmlValidator\Output\BaseTest;

class IgnoreAmperandEncodingIssuesTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        parent::setUp();
    }

    public function testWhenDisabledHasThreeErrors() {
        $output = $this->getParser()->parse($this->getFixture('3-errors.txt'));

        $this->assertEquals(3, $output->getErrorCount());
    }


    public function testWhenEnabledHasOneError() {
        $this->getParser()->getConfiguration()->enableIgnoreAmpersandEncodingIssues();
        $output = $this->getParser()->parse($this->getFixture('3-errors.txt'));

        $this->assertEquals(1, $output->getErrorCount());
    }
}