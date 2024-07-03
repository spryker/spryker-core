<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantCommissionConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\MerchantCommissionItemCollectorRuleSpecificationProviderPlugin;
use Spryker\Zed\ProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission\ProductAttributeMerchantCommissionItemCollectorRulePlugin;
use SprykerTest\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantCommissionConnector
 * @group Business
 * @group Facade
 * @group CollectByProductAttributeTest
 * Add your own group annotations below this line
 */
class CollectByProductAttributeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineDependencyProvider::PLUGINS_RULE_SPECIFICATION_PROVIDER
     *
     * @var string
     */
    protected const PLUGINS_RULE_SPECIFICATION_PROVIDER = 'PLUGINS_RULE_SPECIFICATION_PROVIDER';

    /**
     * @uses \Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider::PLUGINS_RULE_ENGINE_COLLECTOR_RULE
     *
     * @var string
     */
    protected const PLUGINS_RULE_ENGINE_COLLECTOR_RULE = 'PLUGINS_RULE_ENGINE_COLLECTOR_RULE';

    /**
     * @var string
     */
    protected const ATTRIBUTE_KEY_COLOR = 'color';

    /**
     * @var \SprykerTest\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorBusinessTester
     */
    protected ProductMerchantCommissionConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_RULE_SPECIFICATION_PROVIDER, [
            new MerchantCommissionItemCollectorRuleSpecificationProviderPlugin(),
        ]);

        $this->tester->setDependency(static::PLUGINS_RULE_ENGINE_COLLECTOR_RULE, [
            new ProductAttributeMerchantCommissionItemCollectorRulePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldReturnAllItemsSatisfyingProvidedClause(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ATTRIBUTES => [
                static::ATTRIBUTE_KEY_COLOR => 'yellow',
            ],
            true,
        ]);

        $productConcrete1Transfer = $this->tester->haveProductConcreteWithLocalizedAttributes(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [static::ATTRIBUTE_KEY_COLOR => 'blue'],
        );
        $productConcrete2Transfer = $this->tester->haveProductConcreteWithLocalizedAttributes(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
        );
        $productConcrete3Transfer = $this->tester->haveProductConcreteWithLocalizedAttributes(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [static::ATTRIBUTE_KEY_COLOR => 'red'],
        );

        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'attribute',
            RuleEngineClauseTransfer::ATTRIBUTE => 'color',
            RuleEngineClauseTransfer::VALUE => 'blue;yellow',
            RuleEngineClauseTransfer::OPERATOR => 'IS IN',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'string', 'number'],
        ]))->build();
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete1Transfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete2Transfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete3Transfer->getSkuOrFail()])
            ->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByProductAttribute(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertCount(2, $collectedItems);
        $this->assertTrue($this->isItemCollected($collectedItems, $productConcrete1Transfer->getSkuOrFail()));
        $this->assertTrue($this->isItemCollected($collectedItems, $productConcrete2Transfer->getSkuOrFail()));
    }

    /**
     * @return void
     */
    public function testShouldReturnAllItemsWithSameSkuSatisfyingProvidedClause(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productConcreteTransfer = $this->tester->haveProductConcreteWithLocalizedAttributes(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [static::ATTRIBUTE_KEY_COLOR => 'red'],
        );

        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'attribute',
            RuleEngineClauseTransfer::ATTRIBUTE => 'color',
            RuleEngineClauseTransfer::VALUE => 'red',
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'string', 'number'],
        ]))->build();
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcreteTransfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcreteTransfer->getSkuOrFail()])
            ->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByProductAttribute(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertCount(2, $collectedItems);
        $this->assertTrue($this->isItemCollected($collectedItems, $productConcreteTransfer->getSkuOrFail()));
        $this->assertTrue($this->isItemCollected($collectedItems, $productConcreteTransfer->getSkuOrFail()));
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenNoItemSatisfiedProvidedClause(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ATTRIBUTES => [
                static::ATTRIBUTE_KEY_COLOR => 'yellow',
            ],
            true,
        ]);
        $productConcrete1Transfer = $this->tester->haveProductConcreteWithLocalizedAttributes(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [static::ATTRIBUTE_KEY_COLOR => 'blue'],
        );

        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'attribute',
            RuleEngineClauseTransfer::ATTRIBUTE => 'color',
            RuleEngineClauseTransfer::VALUE => 'red',
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'string', 'number'],
        ]))->build();
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete1Transfer->getSkuOrFail()])
            ->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByProductAttribute(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineClauseTransfer,
        );

        // Assert
        $this->assertCount(0, $collectedItems);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     * @param string $sku
     *
     * @return bool
     */
    protected function isItemCollected(array $merchantCommissionCalculationRequestItemTransfers, string $sku): bool
    {
        foreach ($merchantCommissionCalculationRequestItemTransfers as $merchantCommissionCalculationRequestItemTransfer) {
            if ($merchantCommissionCalculationRequestItemTransfer->getSkuOrFail() === $sku) {
                return true;
            }
        }

        return false;
    }
}
