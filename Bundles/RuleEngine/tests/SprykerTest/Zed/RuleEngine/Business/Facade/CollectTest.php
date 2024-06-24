<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineSpecificationRequestBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
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
 * @group CollectTest
 * Add your own group annotations below this line
 */
class CollectTest extends Unit
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
            $this->createCollectorRuleSpecificationProviderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testReturnsOneCollectedItemsAccordingToProvidedQueryString(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "123"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $collectableItemTransfers = new ArrayObject([
            $this->tester->createTestItemTransfer('123'),
            $this->tester->createTestItemTransfer('456'),
        ]);
        $collectableItemsCollectionTransfer = $this->tester->createCollectableItemCollectionTransfer($collectableItemTransfers);

        // Act
        $collectedItems = $this->tester->getFacade()->collect(
            $collectableItemsCollectionTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $collectedItems);
        $this->assertTrue($this->isItemCollected('123', $collectedItems));
    }

    /**
     * @return void
     */
    public function testReturnsTwoCollectedItemsAccordingToProvidedQueryStringWithOrExpression(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "123" OR test-field = "456"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $collectableItemTransfers = new ArrayObject([
            $this->tester->createTestItemTransfer('123'),
            $this->tester->createTestItemTransfer('456'),
        ]);
        $collectableItemsCollectionTransfer = $this->tester->createCollectableItemCollectionTransfer($collectableItemTransfers);

        // Act
        $collectedItems = $this->tester->getFacade()->collect(
            $collectableItemsCollectionTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertCount(2, $collectedItems);
        $this->assertTrue($this->isItemCollected('123', $collectedItems));
        $this->assertTrue($this->isItemCollected('456', $collectedItems));
    }

    /**
     * @return void
     */
    public function testReturnsOneCollectedItemsAccordingToProvidedQueryStringWithAndExpression(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "456" AND test-field = "456"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $collectableItemTransfers = new ArrayObject([
            $this->tester->createTestItemTransfer('123'),
            $this->tester->createTestItemTransfer('456'),
        ]);
        $collectableItemsCollectionTransfer = $this->tester->createCollectableItemCollectionTransfer($collectableItemTransfers);

        // Act
        $collectedItems = $this->tester->getFacade()->collect(
            $collectableItemsCollectionTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $collectedItems);
        $this->assertTrue($this->isItemCollected('456', $collectedItems));
    }

    /**
     * @return void
     */
    public function testReturnsEmptyCollectionWhenNoneOfItemsFulfilQuery(): void
    {
        // Arrange
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestBuilder([
            RuleEngineSpecificationRequestTransfer::QUERY_STRING => 'test-field = "456" AND test-field = "123"',
        ]))->withRuleEngineSpecificationProviderRequest([
            RuleEngineSpecificationProviderRequestTransfer::DOMAIN_NAME => static::TEST_DOMAIN_NAME,
        ])->build();

        $collectableItemTransfers = new ArrayObject([
            $this->tester->createTestItemTransfer('123'),
            $this->tester->createTestItemTransfer('456'),
        ]);
        $collectableItemsCollectionTransfer = $this->tester->createCollectableItemCollectionTransfer($collectableItemTransfers);

        // Act
        $collectedItems = $this->tester->getFacade()->collect(
            $collectableItemsCollectionTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $collectedItems);
    }

    /**
     * @param string $expectedValue
     * @param list<\Spryker\Shared\Kernel\Transfer\TransferInterface> $collectedItems
     *
     * @return bool
     */
    protected function isItemCollected(string $expectedValue, array $collectedItems): bool
    {
        foreach ($collectedItems as $collectedItem) {
            if ($collectedItem->getTestField() === $expectedValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    protected function createCollectorRuleSpecificationProviderPlugin(): RuleSpecificationProviderPluginInterface
    {
        return new TestCollectorRuleSpecificationProviderPlugin($this->createCollectorRulePlugin());
    }

    /**
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface
     */
    protected function createCollectorRulePlugin(): CollectorRulePluginInterface
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
                $collectedItems = [];
                foreach ($collectableTransfer->getCollectableItemTransfers() as $collectableItemTransfer) {
                    if ($collectableItemTransfer->getTestField() === $ruleEngineClauseTransfer->getValue()) {
                        $collectedItems[] = $collectableItemTransfer;
                    }
                }

                return $collectedItems;
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
