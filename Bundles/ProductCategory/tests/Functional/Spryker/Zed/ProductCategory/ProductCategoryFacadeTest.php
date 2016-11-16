<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductCategory;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductCategory
 * @group ProductCategoryFacadeTest
 */
class ProductCategoryFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductCategory\Business\ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->productFacade = new ProductFacade();
        $this->categoryFacade = new CategoryFacade();
        $this->productCategoryFacade = new ProductCategoryFacade();
        $this->productCategoryQueryContainer = new ProductQueryContainer();
    }

    /**
     * @group ProductCategory
     *
     * @return void
     */
    public function testCreateAttributeTypeCreatesAndReturnsId()
    {
        $abstractSku = 'AnAbstractTestProduct';
        $concreteSku = 'ATestProduct';
        $categoryName = 'ATestCategory';
        $localeName = 'ABCDE';
        $abstractName = 'abstractName';
        $categoryKey = '100TEST';

        $locale = $this->localeFacade->createLocale($localeName);

        $localizedAttributesTransfer = $this->buildLocalizedAttributesTransfer($locale, $abstractName);
        $productAbstractTransfer = $this->buildProductAbstractTransfer($abstractSku, $localizedAttributesTransfer);
        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstractTransfer);

        $productConcreteTransfer = $this->buildProductConcreteTransfer($concreteSku, $localizedAttributesTransfer);
        $this->productFacade->createProductConcrete($productConcreteTransfer, $idProductAbstract);

        $categoryTransfer = $this->buildCategoryTransfer($categoryKey, $categoryName, $locale);
        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $locale);

        $categoryNodeTransfer = $this->buildCategoryNodeTransfer($idCategory);
        $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $locale, false);
        $this->productCategoryFacade->createProductCategoryMapping($abstractSku, $categoryName, $locale);

        $this->assertTrue(
            $this->productCategoryFacade->hasProductCategoryMapping(
                $abstractSku,
                $categoryName,
                $locale
            )
        );
    }

    /**
     * @group ProductCategory
     *
     * @return void
     */
    public function testDeleteCategoryWithParentsDeletesAllItsNodes()
    {
        $parentCategoryName1 = 'AParent';
        $parentCategoryName2 = 'BParent';
        $parentCategoryName3 = 'CParent';
        $childCategoryName = 'Child';

        $localeName = 'ABCDE';

        $locale = $this->localeFacade->createLocale($localeName);

        // Prepare category tree: 3 root "parent" categories, 1 child that belongs to 2 parents.

        list($parentCategoryId1, $parentNodeId1) = $this->createDummyRootCategoryWithNode($parentCategoryName1, $locale);
        list($parentCategoryId2, $parentNodeId2) = $this->createDummyRootCategoryWithNode($parentCategoryName2, $locale);

        $childCategory = new CategoryTransfer();
        $childCategory->setName($childCategoryName);
        $childCategory->setCategoryKey(strtolower($childCategoryName));
        $idChildCategory = $this->categoryFacade->createCategory($childCategory, $locale);

        $childNode1 = new NodeTransfer();
        $childNode1->setFkCategory($idChildCategory);
        $childNode1->setFkParentCategoryNode($parentNodeId1);

        $childNode2 = new NodeTransfer();
        $childNode2->setFkCategory($idChildCategory);
        $childNode2->setFkParentCategoryNode($parentNodeId2);

        $this->categoryFacade->createCategoryNode($childNode1, $locale, false);
        $childNodeId2 = $this->categoryFacade->createCategoryNode($childNode2, $locale, false);

        list($parentCategoryId3, $parentNodeId3) = $this->createDummyRootCategoryWithNode($parentCategoryName3, $locale);

        $this->assertNotEquals($parentCategoryId3, $parentNodeId3);

        // Test that removing child category will also remove it's nodes from other parents
        $this->productCategoryFacade->deleteCategory($childNodeId2, $parentNodeId2, true, $locale);

        $parent1Children = $this->categoryFacade->getChildren($parentNodeId1, $locale);
        $parent2Children = $this->categoryFacade->getChildren($parentNodeId2, $locale);

        $this->assertEquals($parent1Children->count(), 0);
        $this->assertEquals($parent2Children->count(), 0);

        // Test removing of a category for which nodeId != categoryId works as well
        $this->productCategoryFacade->deleteCategory($parentNodeId3, 0, true, $locale);

        $result = $this->categoryFacade->getAllNodesByIdCategory($parentCategoryId3);

        $this->assertEmpty($result);
    }

    /**
     * @param string $name
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function createDummyRootCategoryWithNode($name, $locale)
    {
        $parentCategory1 = new CategoryTransfer();
        $parentCategory1->setName($name);
        $parentCategory1->setCategoryKey(strtolower($name));
        $idCategory = $this->categoryFacade->createCategory($parentCategory1, $locale);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsRoot(true);
        $idNode = $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $locale, false);

        return [$idCategory, $idNode];
    }

    /**
     * @param string $categoryKey
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function buildCategoryTransfer($categoryKey, $categoryName, LocaleTransfer $localeTransfer)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setCategoryKey($categoryKey);

        $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
        $categoryLocalizedAttributesTransfer->setName($categoryName);
        $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);
        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);

        return $categoryTransfer;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function buildProductConcreteTransfer($concreteSku, LocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($concreteSku);
        $productConcreteTransfer->setAttributes([]);
        $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        $productConcreteTransfer->setIsActive(true);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $abstractName
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function buildLocalizedAttributesTransfer(LocaleTransfer $localeTransfer, $abstractName)
    {
        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setAttributes([]);
        $localizedAttributes->setLocale($localeTransfer);
        $localizedAttributes->setName($abstractName);

        return $localizedAttributes;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function buildProductAbstractTransfer($abstractSku, LocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($abstractSku);
        $productAbstractTransfer->setAttributes([]);
        $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function buildCategoryNodeTransfer($idCategory)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsRoot(true);

        return $categoryNodeTransfer;
    }

}
