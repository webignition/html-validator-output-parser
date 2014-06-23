<?php

namespace webignition\Tests\HtmlValidator\Output\Parser\Configuration;

use webignition\Tests\HtmlValidator\Output\BaseTest;
use webignition\HtmlValidator\Output\Parser\Configuration;

abstract class ConfigurationTest extends BaseTest {


    /**
     * @var Configuration
     */
    private $configuration;
    
    public function setUp() {
        parent::setUp();
        $this->configuration = new Configuration();
    }


    /**
     * @return Configuration
     */
    protected function getConfiguration() {
        return $this->configuration;
    }
    
}