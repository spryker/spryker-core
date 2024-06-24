<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineQueryStringValidationRequestBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\TestCollectorRuleSpecificationProviderPlugin;
use SprykerTest\Zed\RuleEngine\RuleEngineBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Facade
 * @group ValidateQueryStringTest
 * Add your own group annotations below this line
 */
class ValidateQueryStringTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::PLUGINS_RULE_SPECIFICATION_PROVIDER
     *
     * @var string
     */
    public const PLUGINS_RULE_SPECIFICATION_PROVIDER = 'PLUGINS_RULE_SPECIFICATION_PROVIDER';

    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_INVALID_QUERY_STRING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_QUERY_STRING = 'rule_engine.validation.invalid_query_string';

    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE = 'rule_engine.validation.invalid_compare_operator_value';

    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::COLLECTOR_RULE_SPECIFICATION_TYPE
     *
     * @var string
     */
    protected const COLLECTOR_RULE_SPECIFICATION_TYPE = 'collector';

    /**
     * @var string
     */
    protected const TEST_DOMAIN_NAME = 'test-domain-name';

    /**
     * @var \SprykerTest\Zed\RuleEngine\RuleEngineBusinessTester
     */
    protected RuleEngineBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_RULE_SPECIFICATION_PROVIDER, [
            $this->createCollectorRuleSpecificationProviderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyErrorCollectionWhenQueryStringIsValid(): void
    {
        // Arrange
        $ruleEngineQueryStringValidationRequestTransfer = (new RuleEngineQueryStringValidationRequestBuilder([
            RuleEngineQueryStringValidationRequestTransfer::QUERY_STRINGS => [
                'test-field > "1"',
                'test-field is in "1;2;3"',
            ],
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
            RuleEngineSpecificationProviderRequestTransfer::SPECIFICATION_RULE_TYPE => static::COLLECTOR_RULE_SPECIFICATION_TYPE,
        ])->build();

        // Act
        $ruleEngineQueryStringValidationResponseTransfer = $this->tester->getFacade()->validateQueryString(
            $ruleEngineQueryStringValidationRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $ruleEngineQueryStringValidationResponseTransfer->getErrors());
    }

    /**
     * @dataProvider shouldReturnErrorWhenInvalidQueryStringIsProvidedDataProvider
     *
     * @param string $queryString
     * @param string $expectedError
     *
     * @return void
     */
    public function testShouldReturnErrorWhenInvalidQueryStringIsProvided(string $queryString, string $expectedError): void
    {
        // Arrange
        $ruleEngineQueryStringValidationRequestTransfer = (new RuleEngineQueryStringValidationRequestBuilder([
            RuleEngineQueryStringValidationRequestTransfer::QUERY_STRINGS => [$queryString],
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
            RuleEngineSpecificationProviderRequestTransfer::SPECIFICATION_RULE_TYPE => static::COLLECTOR_RULE_SPECIFICATION_TYPE,
        ])->build();

        // Act
        $ruleEngineQueryStringValidationResponseTransfer = $this->tester->getFacade()->validateQueryString(
            $ruleEngineQueryStringValidationRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $ruleEngineQueryStringValidationResponseTransfer->getErrors());
        $this->assertSame($expectedError, $ruleEngineQueryStringValidationResponseTransfer->getErrors()->getIterator()->current()->getMessage());
    }

    /**
     * @return array<string, list<string>>
     */
    protected function shouldReturnErrorWhenInvalidQueryStringIsProvidedDataProvider(): array
    {
        return [
            'invalid compare operator' => [
                'test-field === "1"',
                static::GLOSSARY_KEY_INVALID_QUERY_STRING,
            ],
            'unknown field' => [
                'unknown-field = "value"',
                static::GLOSSARY_KEY_INVALID_QUERY_STRING,
            ],
            'invalid parentheses' => [
                '(test-field = "value"',
                static::GLOSSARY_KEY_INVALID_QUERY_STRING,
            ],
            'invalid compare value' => [
                'test-field >= "string"',
                static::GLOSSARY_KEY_INVALID_COMPARE_OPERATOR_VALUE,
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    protected function createCollectorRuleSpecificationProviderPlugin(): RuleSpecificationProviderPluginInterface
    {
        return new TestCollectorRuleSpecificationProviderPlugin($this->createRulePlugin());
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface
     */
    protected function createRulePlugin(): CollectorRulePluginInterface
    {
        return new class () implements CollectorRulePluginInterface {
            /**
             * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
             * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
             *
             * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
             */
            public function collect(TransferInterface $collectableTransfer, RuleEngineClauseTransfer $ruleEngineClauseTransfer): array
            {
                return [];
            }

            /**
             * @return string
             */
            public function getFieldName(): string
            {
                return 'test-field';
            }

            /**
             * @return list<string>
             */
            public function acceptedDataTypes(): array
            {
                return [
                    'number',
                    'list',
                ];
            }
        };
    }
}
