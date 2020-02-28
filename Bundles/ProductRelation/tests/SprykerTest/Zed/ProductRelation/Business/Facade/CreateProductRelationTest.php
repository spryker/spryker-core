<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductRelationBuilder;
use Generated\Shared\DataBuilder\ProductRelationTypeBuilder;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Business
 * @group Facade
 * @group CreateProductRelationTest
 * Add your own group annotations below this line
 */
class CreateProductRelationTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductRelation\ProductRelationBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productRelationFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testCreateProductRelationShouldCreateProductRelationWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureProductRelationTableIsEmpty();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->addIdStores($storeTransfer->getIdStore())
            ->addStores($storeTransfer);
        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setCondition('AND');

        $ruleQuerySetTransfer->addRules($this->createProductAbstractSkuRuleTransfer('test'));

        $ruleQuerySetTransfer->addRules($this->createProductCategoryNameRuleTransfer('test'));
        $productRelationTypeTransfer = (new ProductRelationTypeBuilder())->seed([
            ProductRelationTypeTransfer::KEY => 'up-selling',
        ])->build();

        $productRelationTransfer = (new ProductRelationBuilder())->seed([
            ProductRelationTransfer::PRODUCT_RELATION_TYPE => $productRelationTypeTransfer,
            ProductRelationTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductRelationTransfer::PRODUCT_RELATION_KEY => 'test1',
            ProductRelationTransfer::STORE_RELATION => $storeRelationTransfer,
            ProductRelationTransfer::QUERY_SET => $ruleQuerySetTransfer,
        ])->build();

        // Act
        $productRelationResponseTransfer = $this->productRelationFacade->createProductRelation($productRelationTransfer);

        // Assert
        $productRelationExist = SpyProductRelationQuery::create()
            ->filterByProductRelationKey('test1')
            ->exists();
        $storeRelationExist = SpyProductRelationStoreQuery::create()
            ->useProductRelationQuery()
                ->filterByProductRelationKey('test1')
            ->endUse()
            ->exists();

        $this->assertTrue($productRelationExist, 'Product relation should exists');
        $this->assertTrue($storeRelationExist, 'Product relation store relation should exists');
    }

    /**
     * @param string $skuValueForFilter
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function createProductAbstractSkuRuleTransfer(string $skuValueForFilter): PropelQueryBuilderRuleSetTransfer
    {
        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setId('spy_product_abstract');
        $ruleQuerySetTransfer->setField('spy_product_abstract.sku');
        $ruleQuerySetTransfer->setType('string');
        $ruleQuerySetTransfer->setInput('text');
        $ruleQuerySetTransfer->setOperator('equal');
        $ruleQuerySetTransfer->setValue($skuValueForFilter);

        return $ruleQuerySetTransfer;
    }

    /**
     * @param string $categoryName
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function createProductCategoryNameRuleTransfer(string $categoryName): PropelQueryBuilderRuleSetTransfer
    {
        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setId('product_category_name');
        $ruleQuerySetTransfer->setField('spy_category_attribute.name');
        $ruleQuerySetTransfer->setType('string');
        $ruleQuerySetTransfer->setInput('text');
        $ruleQuerySetTransfer->setOperator('equal');
        $ruleQuerySetTransfer->setValue($categoryName);

        return $ruleQuerySetTransfer;
    }
}
