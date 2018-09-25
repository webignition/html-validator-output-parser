<?php

namespace webignition\Tests\HtmlValidator\Parser;

use webignition\HtmlValidator\Output\Parser\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
    }

    /**
     * @dataProvider createConfigurationDataProvider
     *
     * @param array $configurationValues
     * @param bool $expectedIgnoreAmpersandEncodingIssues
     * @param bool $expectedIgnoreCssValidationIssues
     */
    public function testCreateConfiguration(
        array $configurationValues,
        bool $expectedIgnoreAmpersandEncodingIssues,
        bool $expectedIgnoreCssValidationIssues
    ) {
        $configuration = new Configuration($configurationValues);

        $this->assertEquals($expectedIgnoreAmpersandEncodingIssues, $configuration->getIgnoreAmpersandEncodingIssues());
        $this->assertEquals($expectedIgnoreCssValidationIssues, $configuration->getIgnoreCssValidationIssues());
    }

    public function createConfigurationDataProvider(): array
    {
        return [
            'default' => [
                'configurationValues' => [],
                'expectedIgnoreAmpersandEncodingIssues' => false,
                'expectedIgnoreCssValidationIssues' => false,
            ],
            'ignore ampersand encoding issues only' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                ],
                'expectedIgnoreAmpersandEncodingIssues' => true,
                'expectedIgnoreCssValidationIssues' => false,
            ],
            'ignore css validation issues only' => [
                'configurationValues' => [
                    Configuration::KEY_CSS_VALIDATION_ISSUES => true,
                ],
                'expectedIgnoreAmpersandEncodingIssues' => false,
                'expectedIgnoreCssValidationIssues' => true,
            ],
            'ignore ampersand encoding issues, ignore css validation issues only' => [
                'configurationValues' => [
                    Configuration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                    Configuration::KEY_CSS_VALIDATION_ISSUES => true,
                ],
                'expectedIgnoreAmpersandEncodingIssues' => true,
                'expectedIgnoreCssValidationIssues' => true,
            ],
        ];
    }
}
