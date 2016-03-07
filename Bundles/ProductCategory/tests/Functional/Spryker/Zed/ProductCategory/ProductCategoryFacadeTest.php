<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductCategory;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryTransfer;
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
 * @group Spryker
 * @group Zed
 * @group ProductCategory
 * @group ProductCategoryFacade
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

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($abstractSku);
        $productAbstractTransfer->setAttributes([]);
        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setAttributes([]);
        $localizedAttributes->setLocale($locale);
        $localizedAttributes->setName($abstractName);
        $productAbstractTransfer->addLocalizedAttributes($localizedAttributes);
        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstractTransfer);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($concreteSku);
        $productConcreteTransfer->setAttributes([]);
        $productConcreteTransfer->addLocalizedAttributes($localizedAttributes);
        $productConcreteTransfer->setIsActive(true);
        $this->productFacade->createProductConcrete($productConcreteTransfer, $idProductAbstract);

        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($categoryName);
        $categoryTransfer->setCategoryKey($categoryKey);
        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $locale);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsRoot(true);
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

}
