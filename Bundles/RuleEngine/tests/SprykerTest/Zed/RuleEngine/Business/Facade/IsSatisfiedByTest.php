<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineSpecificationRequestBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\TestDecisionRuleSpecificationProviderPlugin;
use SprykerTest\Zed\RuleEngine\RuleEngineBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Facade
 * @group IsSatisfiedByTest
 * Add your own group annotations below this line
 */
class IsSatisfiedByTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::PLUGINS_RULE_SPECIFICATION_PROVIDER
     *
     * @var string
     */
    public const PLUGINS_RULE_SPECIFICATION_PROVIDER = 'PLUGINS_RULE_SPECIFICATION_PROVIDER';

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
            $this->createDecisionRuleSpecificationProviderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testReturnsTrueAccordingToProvidedQueryString(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "123"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $satisfyingTransfer = $this->tester->createTestItemTransfer('123');

        // Act
        $result = $this->tester->getFacade()->isSatisfiedBy(
            $satisfyingTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReturnsTrueAccordingToProvidedQueryStringWithOrExpression(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "123" OR test-field = "456"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $satisfyingTransfer = $this->tester->createTestItemTransfer('123');

        // Act
        $result = $this->tester->getFacade()->isSatisfiedBy(
            $satisfyingTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReturnsTrueAccordingToProvidedQueryStringWithAndExpression(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "123" AND test-field = "123"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $satisfyingTransfer = $this->tester->createTestItemTransfer('123');

        // Act
        $result = $this->tester->getFacade()->isSatisfiedBy(
            $satisfyingTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenItemNotSatisfiesQuery(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "456"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $satisfyingTransfer = $this->tester->createTestItemTransfer('123');

        // Act
        $result = $this->tester->getFacade()->isSatisfiedBy(
            $satisfyingTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    protected function createDecisionRuleSpecificationProviderPlugin(): RuleSpecificationProviderPluginInterface
    {
        return new TestDecisionRuleSpecificationProviderPlugin($this->createDecisionRulePlugin());
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface
     */
    protected function createDecisionRulePlugin(): DecisionRulePluginInterface
    {
        return new class () implements DecisionRulePluginInterface {
            /**
             * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
             * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
             *
             * @return bool
             */
            public function isSatisfiedBy(TransferInterface $satisfyingTransfer, RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
            {
                return $satisfyingTransfer->getTestField() === $ruleEngineClauseTransfer->getValue();
            }

            /**
             * @return string
             */
            public function getFieldName(): string
            {
                return 'test-field';
            }

            /**
             * @return array<string>
             */
            public function acceptedDataTypes(): array
            {
                return ['string', 'numeric'];
            }
        };
    }
}
