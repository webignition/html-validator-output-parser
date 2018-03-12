<?php

namespace webignition\Tests\HtmlValidator\Output;

use webignition\HtmlValidator\Output\Parser\Parser;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {

    const FIXTURES_BASE_PATH = '/fixtures';

    /**
     *
     * @var string
     */
    private $fixturePath = null;


    /**
     * @var Parser;
     */
    private $parser;


    public function setUp() {
        parent::setUp();
        $this->parser = new Parser();
    }


    /**
     * @return Parser
     */
    protected function getParser() {
        return $this->parser;
    }


    /**
     *
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod) {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . $testClass . '/' . $testMethod;
    }


    /**
     *
     * @return string
     */
    protected function getTestFixturePath() {
        return $this->fixturePath;
    }


    /**
     *
     * @param string $fixtureName
     * @return string
     */
    protected function getFixture($fixtureName) {
        if (file_exists($this->getTestFixturePath() . '/' . $fixtureName)) {
            return file_get_contents($this->getTestFixturePath() . '/' . $fixtureName);
        }

        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . '/Common/' . $fixtureName);
    }
}