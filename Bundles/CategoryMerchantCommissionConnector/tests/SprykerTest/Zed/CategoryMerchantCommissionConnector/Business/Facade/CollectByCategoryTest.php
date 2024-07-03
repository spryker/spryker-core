<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryMerchantCommissionConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\CategoryMerchantCommissionConnector\Communication\Plugin\MerchantCommission\CategoryMerchantCommissionItemCollectorRulePlugin;
use Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\MerchantCommissionItemCollectorRuleSpecificationProviderPlugin;
use SprykerTest\Zed\CategoryMerchantCommissionConnector\CategoryMerchantCommissionConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryMerchantCommissionConnector
 * @group Business
 * @group Facade
 * @group CollectByCategoryTest
 * Add your own group annotations below this line
 */
class CollectByCategoryTest extends Unit
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
    protected const PARENT_CATEGORY_KEY = 'parent-category-key';

    /**
     * @var string
     */
    protected const CHILD_CATEGORY_KEY = 'child-category-key';

    /**
     * @var \SprykerTest\Zed\CategoryMerchantCommissionConnector\CategoryMerchantCommissionConnectorBusinessTester
     */
    protected CategoryMerchantCommissionConnectorBusinessTester $tester;

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
            new CategoryMerchantCommissionItemCollectorRulePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldReturnAllItemsSatisfyingProvidedClause(): void
    {
        // Arrange
        $productConcrete1Transfer = $this->tester->haveFullProduct();
        $productConcrete2Transfer = $this->tester->haveFullProduct();
        $productConcrete3Transfer = $this->tester->haveFullProduct();
        $productConcrete4Transfer = $this->tester->haveFullProduct();

        $parentCategoryTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::PARENT_CATEGORY_KEY,
        ]);
        $childCategoryTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::CHILD_CATEGORY_KEY,
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNodeOrFail(),
        ]);
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $this->tester->haveProductCategoryForCategory($parentCategoryTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete2Transfer->getFkProductAbstractOrFail(),
        ]);
        $this->tester->haveProductCategoryForCategory($childCategoryTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete1Transfer->getFkProductAbstractOrFail(),
        ]);
        $this->tester->haveProductCategoryForCategory($categoryTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete1Transfer->getFkProductAbstractOrFail(),
        ]);

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete1Transfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete2Transfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete3Transfer->getSkuOrFail()])
            ->withAnotherItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcrete4Transfer->getSkuOrFail()])
            ->build();
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'category',
            RuleEngineClauseTransfer::VALUE => static::PARENT_CATEGORY_KEY,
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'string'],
        ]))->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByCategory(
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
    public function testShouldReturnEmptyCollectionWhenNoItemSatisfiedProvidedClause(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $categoryTransfer = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::PARENT_CATEGORY_KEY]);
        $this->tester->haveProductCategoryForCategory($categoryTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
        ]);

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder())
            ->withItem([MerchantCommissionCalculationRequestItemTransfer::SKU => $productConcreteTransfer->getSkuOrFail()])
            ->build();
        $ruleEngineClauseTransfer = (new RuleEngineClauseBuilder([
            RuleEngineClauseTransfer::FIELD => 'category',
            RuleEngineClauseTransfer::VALUE => static::CHILD_CATEGORY_KEY,
            RuleEngineClauseTransfer::OPERATOR => '=',
            RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list', 'string'],
        ]))->build();

        // Act
        $collectedItems = $this->tester->getFacade()->collectByCategory(
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
