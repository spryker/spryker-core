<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Business
 * @group Facade
 * @group ProductRelationFacadeTest
 * Add your own group annotations below this line
 */
class ProductRelationFacadeTest extends Unit
{
    public const ID_TEST_LOCALE = 66;

    /**
     * @return void
     */
    public function testCreateProductRelationShouldPersistGivenTransfer()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $this->assertNotEmpty($idProductRelation);
    }

    /**
     * @return void
     */
    public function testUpdateProductRelationShouldUpdateExistingRelation()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $productRelationTransfer->setIdProductRelation($idProductRelation);
        $productRelationTransfer->setIsActive(false);

        $productRelationFacade->updateProductRelation($productRelationTransfer);

        $productRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->assertFalse($productRelationTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testFindProductRelationByIdShouldReturnPersistedRelation()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $persistedProductRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->assertNotNull($persistedProductRelationTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductRelationTypeListShouldReturnPersistedProductRelationTypeList()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $productRelationFacade->createProductRelation($productRelationTransfer);

        $this->assertCount(2, $productRelationFacade->getProductRelationTypeList());
        $this->assertEquals(
            $productRelationTransfer->getProductRelationType()->getKey(),
            $productRelationFacade->getProductRelationTypeList()[0]->getKey()
        );
    }

    /**
     * @return void
     */
    public function testActivateProductRelationShouldActivateRelation()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $productRelationFacade->activateProductRelation($idProductRelation);

        $productRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->assertTrue($productRelationTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateProductRelationShouldDeactivateRelation()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $productRelationFacade->deactivateProductRelation($idProductRelation);

        $productRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->assertFalse($productRelationTransfer->getIsActive());
    }

    /**
     * @param string $categoryName
     * @param array $productToAssign
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createProductCategory($categoryName, array $productToAssign)
    {
        $categoryFacade = $this->createCategoryFacade();

        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setCategoryKey('test-key');

        $nodeTransfer = new NodeTransfer();
        $nodeTransfer->setIsRoot(true);
        $categoryTransfer->setParentCategoryNode($nodeTransfer);

        $nodeTransfer = new NodeTransfer();
        $nodeTransfer->setName($categoryName);

        $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
        $categoryLocalizedAttributesTransfer->setName($categoryName);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale(static::ID_TEST_LOCALE);
        $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);

        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);

        $categoryTransfer->setCategoryNode($nodeTransfer);

        $categoryFacade->create($categoryTransfer);

        $productCategoryFacade = $this->createProductFacade();
        $productCategoryFacade->createProductCategoryMappings($categoryTransfer->getIdCategory(), $productToAssign);

        return $categoryTransfer;
    }

    /**
     * @return void
     */
    public function testDeleteProductRelationShouldDropExistingRelationFromPersistence()
    {
        $productRelationFacade = $this->createProductRelationFacade();
        $productRelationTransfer = $this->createProductRelationTransfer(123);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $deleted = $productRelationFacade->deleteProductRelation($idProductRelation);

        $productRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->assertNull($productRelationTransfer);
        $this->assertTrue($deleted);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function createProductAbstract($sku)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSku($sku);
        $productAbstractEntity->setAttributes('');

        $productAbstractEntity->save();

        return $productAbstractEntity->getIdProductAbstract();
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacade
     */
    protected function createProductRelationFacade()
    {
        return new ProductRelationFacade();
    }

    /**
     * @param string|null $skuValueForFilter
     * @param string|null $categoryName
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationTransfer($skuValueForFilter = null, $categoryName = null)
    {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->setFkProductAbstract($this->createProductAbstract('sku-test-product-relations'));

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setCondition('AND');

        if ($skuValueForFilter !== null) {
            $ruleQuerySetTransfer->addRules($this->createProductAbstractSkuRuleTransfer($skuValueForFilter));
        }

        if ($categoryName !== null) {
            $ruleQuerySetTransfer->addRules($this->createProductCategoryNameRuleTransfer($categoryName));
        }

        $productRelationTransfer->setQuerySet($ruleQuerySetTransfer);
        $productRelationTransfer->setIsActive(true);

        $productRelationTypeTransfer = new ProductRelationTypeTransfer();
        $productRelationTypeTransfer->setKey(ProductRelationTypes::TYPE_UP_SELLING);
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        return $productRelationTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected function createProductFacade()
    {
        return new ProductCategoryFacade();
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function createCategoryFacade()
    {
        return new CategoryFacade();
    }

    /**
     * @param string $skuValueForFilter
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function createProductAbstractSkuRuleTransfer($skuValueForFilter)
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
    protected function createProductCategoryNameRuleTransfer($categoryName)
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
