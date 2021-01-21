<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategorySearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionClass;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageDataExpander;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategorySearch
 * @group Business
 * @group Facade
 * @group ProductCategorySearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductCategorySearchFacadeTest extends Unit
{
    protected const STORE_DE = 'DE';
    protected const FAKE_ID_PRODUCT_ABSTRACT = 6666;

    /**
     * @uses \Spryker\Shared\ProductPageSearch\ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA
     */
    protected const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';

    /**
     * @var \SprykerTest\Zed\ProductCategorySearch\ProductCategorySearchBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer
     */
    protected $categoryTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_DE]);

        $this->localeTransfer = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();
        $this->categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $this->localeTransfer]);

        $this->tester->haveCategoryStoreRelation(
            $this->categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore()
        );

        $this->productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $this->productConcreteTransfer->getFkProductAbstract()
        );
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanStaticProperty();
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransfer(): void
    {
        //Arrange
        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setPayloadTransfers([(new ProductPayloadTransfer())->setIdProductAbstract($this->productConcreteTransfer->getFkProductAbstract())])
            ->setProductAbstractIds([$this->productConcreteTransfer->getFkProductAbstract()]);

        //Act
        $productPageLoadTransfer = $this->tester->getFacade()->expandProductPageDataTransfer($productPageLoadTransfer);

        //Assert
        $this->assertNotEmpty($productPageLoadTransfer->getPayloadTransfers()[0]->getCategories());
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferForFakeAbstractProduct(): void
    {
        //Arrange
        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setPayloadTransfers([(new ProductPayloadTransfer())->setIdProductAbstract(static::FAKE_ID_PRODUCT_ABSTRACT)])
            ->setProductAbstractIds([static::FAKE_ID_PRODUCT_ABSTRACT]);

        //Act
        $productPageLoadTransfer = $this->tester->getFacade()->expandProductPageDataTransfer($productPageLoadTransfer);

        //Assert
        $this->assertEmpty($productPageLoadTransfer->getPayloadTransfers()[0]->getCategories());
    }

    /**
     * @return void
     */
    public function testExpandProductPageMapWithCategoryData(): void
    {
        //Arrange
        $productData = $this->tester->getProductData();

        //Act
        $pageMapTransfer = $this->tester->getFacade()->expandProductPageMapWithCategoryData(
            new PageMapTransfer(),
            new PageMapBuilder(),
            $productData,
            $this->localeTransfer
        );

        //Assert
        $this->assertEquals(
            $this->tester->getExpectedPageMapTransfer()->toArray(true),
            $pageMapTransfer->toArray(true),
            'Expansion of PageMapTransfer was not successful'
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageData(): void
    {
        //Arrange
        $productCategoryEntities = $this->tester->getMappedProductCategoriesByIdProductAbstractAndStore([
            $this->productConcreteTransfer->getFkProductAbstract(),
        ]);

        $productData = [
            'Locale' => ['id_locale' => $this->localeTransfer->getIdLocale()],
            static::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => (new ProductPayloadTransfer())
                ->setCategories($productCategoryEntities[$this->productConcreteTransfer->getFkProductAbstract()] ?? []),
        ];

        $productPageSearchTransfer = (new ProductPageSearchTransfer())
            ->setStore(static::STORE_DE)
            ->setCategoryNodeIds([$this->categoryTransfer->getIdCategory()]);

        //Act
        $this->tester->getFacade()->expandProductPageData(
            $productData,
            $productPageSearchTransfer
        );

        //Assert
        $this->assertNotEmpty($productPageSearchTransfer->getAllParentCategoryIds(), 'Property `allParentCategoryIds` should expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getCategoryNames(), 'Property `categoryNames` should expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getSortedCategories(), 'Property `sortedCategories` should expanded.');
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataWithoutStore(): void
    {
        //Arrange
        $productCategoryEntities = $this->tester->getMappedProductCategoriesByIdProductAbstractAndStore([
            $this->productConcreteTransfer->getFkProductAbstract(),
        ]);

        $productData = [
            'Locale' => ['id_locale' => $this->localeTransfer->getIdLocale()],
            static::PRODUCT_ABSTRACT_PAGE_LOAD_DATA => (new ProductPayloadTransfer())
                ->setCategories($productCategoryEntities[$this->productConcreteTransfer->getFkProductAbstract()] ?? []),
        ];

        $productPageSearchTransfer = (new ProductPageSearchTransfer())
            ->setStore(null)
            ->setCategoryNodeIds([$this->categoryTransfer->getIdCategory()]);

        //Act
        $this->tester->getFacade()->expandProductPageData(
            $productData,
            $productPageSearchTransfer
        );

        //Assert
        $this->assertNotEmpty($productPageSearchTransfer->getAllParentCategoryIds(), 'Property `allParentCategoryIds` should expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getCategoryNames(), 'Property `categoryNames` should expanded.');
        $this->assertEmpty($productPageSearchTransfer->getSortedCategories(), 'Property `sortedCategories` should empty.');
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(ProductPageDataExpander::class);

        $propertyCategoryTree = $reflectedClass->getProperty('categoryTree');
        $propertyCategoryTree->setAccessible(true);
        $propertyCategoryTree->setValue(null);

        $propertyCategoryNames = $reflectedClass->getProperty('categoryNames');
        $propertyCategoryNames->setAccessible(true);
        $propertyCategoryNames->setValue(null);
    }
}
