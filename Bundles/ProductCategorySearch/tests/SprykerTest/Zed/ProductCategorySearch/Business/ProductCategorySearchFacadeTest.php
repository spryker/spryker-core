<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategorySearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionClass;
use Spryker\Zed\ProductCategorySearch\Business\Builder\ProductCategoryTreeBuilder;
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
    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var int
     */
    protected const FAKE_ID_PRODUCT_ABSTRACT = 6666;

    /**
     * @var string
     */
    protected const TEST_CATEGORY_NAME = 'Test category';

    /**
     * @uses \Spryker\Shared\ProductPageSearch\ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA
     *
     * @var string
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
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_DE]);

        $this->localeTransfer = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();
        $this->categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $this->localeTransfer]);

        $this->tester->haveCategoryStoreRelation(
            $this->categoryTransfer->getIdCategory(),
            $this->storeTransfer->getIdStore(),
        );

        $this->productConcreteTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory(
            $this->categoryTransfer->getIdCategory(),
            $this->productConcreteTransfer->getFkProductAbstract(),
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
    public function testExpandProductPageWithCategories(): void
    {
        //Arrange
        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setPayloadTransfers([(new ProductPayloadTransfer())->setIdProductAbstract($this->productConcreteTransfer->getFkProductAbstract())])
            ->setProductAbstractIds([$this->productConcreteTransfer->getFkProductAbstract()]);

        //Act
        $productPageLoadTransfer = $this->tester->getFacade()->expandProductPageWithCategories($productPageLoadTransfer);

        //Assert
        $this->assertNotEmpty($productPageLoadTransfer->getPayloadTransfers()[0]->getCategories());
    }

    /**
     * @return void
     */
    public function testExpandProductPageWithCategoriesForFakeAbstractProduct(): void
    {
        //Arrange
        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setPayloadTransfers([(new ProductPayloadTransfer())->setIdProductAbstract(static::FAKE_ID_PRODUCT_ABSTRACT)])
            ->setProductAbstractIds([static::FAKE_ID_PRODUCT_ABSTRACT]);

        //Act
        $productPageLoadTransfer = $this->tester->getFacade()->expandProductPageWithCategories($productPageLoadTransfer);

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
            $this->localeTransfer,
        );

        //Assert
        $this->assertEquals(
            $this->tester->getExpectedPageMapTransfer()->toArray(true),
            $pageMapTransfer->toArray(true),
            'Expansion of PageMapTransfer was not successful',
        );
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataWithCategoryData(): void
    {
        //Arrange
        $categoryNodeTransfer = $this->tester->haveCategoryNodeWithDifferentIdCategory(
            [
                CategoryTransfer::FK_CATEGORY_TEMPLATE => $this->categoryTransfer->getFkCategoryTemplate(),
            ],
            [
                CategoryLocalizedAttributesTransfer::LOCALE => $this->localeTransfer,
                CategoryLocalizedAttributesTransfer::NAME => static::TEST_CATEGORY_NAME,
            ],
            [
                ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getFkProductAbstract(),
            ],
            $this->storeTransfer->getIdStore(),
        );
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
            ->setCategoryNodeIds([$this->categoryTransfer->getCategoryNode()->getIdCategoryNode()]);

        //Act
        $this->tester->getFacade()->expandProductPageDataWithCategoryData(
            $productData,
            $productPageSearchTransfer,
        );

        //Assert
        $idCategoryNode = $categoryNodeTransfer->getIdCategoryNode();
        $boostedCategoryNames = $productPageSearchTransfer->getBoostedCategoryNames();
        $this->assertNotEmpty($productPageSearchTransfer->getAllParentCategoryIds(), 'Property `allParentCategoryIds` should be expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getCategoryNames(), 'Property `categoryNames` should be expanded.');
        $this->assertNotEmpty($boostedCategoryNames, 'Property `boostedCategoryNames` should be expanded.');
        $this->assertArrayHasKey($idCategoryNode, $boostedCategoryNames);
        $this->assertEquals(static::TEST_CATEGORY_NAME, $boostedCategoryNames[$idCategoryNode]);
        $this->assertNotEmpty($productPageSearchTransfer->getSortedCategories(), 'Property `sortedCategories` should be expanded.');
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataWithCategoryDataWithoutStore(): void
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
            ->setCategoryNodeIds([$this->categoryTransfer->getCategoryNode()->getIdCategoryNode()]);

        //Act
        $this->tester->getFacade()->expandProductPageDataWithCategoryData(
            $productData,
            $productPageSearchTransfer,
        );

        //Assert
        $this->assertEmpty($productPageSearchTransfer->getAllParentCategoryIds(), 'Property `allParentCategoryIds` should be empty.');
        $this->assertEmpty($productPageSearchTransfer->getCategoryNames(), 'Property `categoryNames` should be empty.');
        $this->assertEmpty($productPageSearchTransfer->getSortedCategories(), 'Property `sortedCategories` should be empty.');
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataWithOneOfCategoriesWithoutStoreWillFilterOutIncorrectCategoryNodeId(): void
    {
        //Arrange
        $categoryWithoutStoreRelationTransfer = $this->tester->haveLocalizedCategory(['locale' => $this->localeTransfer]);
        $this->tester->assignProductToCategory(
            $categoryWithoutStoreRelationTransfer->getIdCategory(),
            $this->productConcreteTransfer->getFkProductAbstract(),
        );

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
            ->setCategoryNodeIds([
                $this->categoryTransfer->getCategoryNode()->getIdCategoryNode(),
                $categoryWithoutStoreRelationTransfer->getCategoryNode()->getIdCategoryNode(),
            ]);

        //Act
        $this->tester->getFacade()->expandProductPageDataWithCategoryData(
            $productData,
            $productPageSearchTransfer,
        );

        //Assert
        $this->assertFalse(
            in_array($categoryWithoutStoreRelationTransfer->getCategoryNode()->getIdCategoryNode(), $productPageSearchTransfer->getCategoryNodeIds()),
            'Category node id without store relation should be filtered out.',
        );
        $this->assertTrue(
            in_array($this->categoryTransfer->getCategoryNode()->getIdCategoryNode(), $productPageSearchTransfer->getCategoryNodeIds()),
            'Category node id with store relation should not be filtered out.',
        );
        $this->assertNotEmpty($productPageSearchTransfer->getAllParentCategoryIds(), 'Property `allParentCategoryIds` should be expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getCategoryNames(), 'Property `categoryNames` should be expanded.');
        $this->assertNotEmpty($productPageSearchTransfer->getSortedCategories(), 'Property `sortedCategories` should be expanded.');
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(ProductCategoryTreeBuilder::class);

        $propertyCategoryTree = $reflectedClass->getProperty('categoryTreeIds');
        $propertyCategoryTree->setAccessible(true);
        $propertyCategoryTree->setValue([]);
    }
}
