<?php

namespace webignition\Tests\HtmlValidator\Output\Parser\Configuration;

class IgnoreAmperandEncodingIssuesTest extends ConfigurationTest {

    public function testDefaultIsFalse() {
        $this->assertFalse($this->getConfiguration()->getIgnoreAmpersandEncodingIssues());
    }


    public function testEnable() {
        $this->getConfiguration()->enableIgnoreAmpersandEncodingIssues();
        $this->assertTrue($this->getConfiguration()->getIgnoreAmpersandEncodingIssues());
    }


    public function testDisable() {
        $this->getConfiguration()->disableIgnoreAmpersandEncodingIssues();
        $this->assertFalse($this->getConfiguration()->getIgnoreAmpersandEncodingIssues());
    }
    
}