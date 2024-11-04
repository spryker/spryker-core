<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\IntegerSortMapTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractCollectionRefreshPluginInterface;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group Facade
 * @group ProductPageSearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductPageSearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester
     */
    protected ProductPageSearchBusinessTester $tester;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    protected ProductPageSearchFacadeInterface $productPageSearchFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setUp();

        $this->productPageSearchFacade = new ProductPageSearchFacade();
    }

    /**
     * @return void
     */
    public function testUnpublishProductConcretePageSearches(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        $productAbstractStoreMap = [
            $productAbstractTransfer->getIdProductAbstract() => [$storeNames[0]],
        ];
        unset($storeNames[0]);
        $this->productPageSearchFacade->unpublishProductConcretePageSearches($productAbstractStoreMap);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertSame($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }

    /**
     * @group testPublishProductConcretePageSearchesByProductAbstractIds
     *
     * @return void
     */
    public function testPublishProductConcretePageSearchesByProductAbstractIds(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertSame($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }

    /**
     * @return void
     */
    public function testPublishProductAbstractPageShouldNotPublishToSearchWithoutStoreConnection(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        // Act
        $this->productPageSearchFacade->publish([$productAbstractTransfer->getIdProductAbstract()]);

        $productAbstractPageSearchCount = $this->tester->getProductAbstractPageSearchCountByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        // Assert
        $this->assertSame(0, $productAbstractPageSearchCount);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsDataWithProductImages(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertContains(
            $productImageSetTransfer->getProductImages()->offsetGet(0)->toArray(false, true),
            $expandedProductConcretePageSearchTransfer->getImages(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutImages(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertEmpty($expandedProductConcretePageSearchTransfer->getImages());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale(['localeName' => 'de_DE']);
        $localeTransfer2 = $this->tester->haveLocale(['localeName' => 'en_US']);

        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer2,
        ]);

        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setLocale($localeTransfer->getLocaleName());

        // Act
        $expandedProductConcretePageSearchTransfer = $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);

        // Assert
        $this->assertEmpty($expandedProductConcretePageSearchTransfer->getImages());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutRequiredLocaleField(): void
    {
        // Arrange
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct($this->tester->haveProduct()->getIdProductConcrete())
            ->setLocale(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductImagesExpandsProductWithoutRequiredIdProductField(): void
    {
        // Arrange
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchTransfer())
            ->setFkProduct(null)
            ->setLocale($this->tester->haveLocale()->getLocaleName());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productPageSearchFacade
            ->expandProductConcretePageSearchTransferWithProductImages($productConcretePageSearchTransfer);
    }

    /**
     * @dataProvider expandProductPageMapWithCategoryDataDataProvider
     *
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\PageMapTransfer $expectedPageMapTransfer
     * @param string $localeName
     *
     * @return void
     */
    public function testExpandProductPageMapWithCategoryDataShouldExpandProductMapDataWithProductCategoryData(
        array $productData,
        PageMapTransfer $expectedPageMapTransfer,
        string $localeName
    ): void {
        // Arrange
        $pageMapTransfer = new PageMapTransfer();
        $pageMapBuilder = $this->createPageMapBuilder();
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => $localeName]);

        // Act
        $actualPageMapTransfer = $this->productPageSearchFacade
            ->expandProductPageMapWithCategoryData(
                $pageMapTransfer,
                $pageMapBuilder,
                $productData,
                $localeTransfer,
            );

        // Assert
        $this->assertEquals($expectedPageMapTransfer->toArray(true), $actualPageMapTransfer->toArray(true));
    }

    /**
     * @return array
     */
    public function expandProductPageMapWithCategoryDataDataProvider(): array
    {
        return [
            'different parent categories' => $this->getDataWithDifferentParentCategories(),
            'parent categories are intersected' => $this->getDataWithParentCategoriesIntersected(),
        ];
    }

    /**
     * @return void
     */
    public function testRefreshProductAbstractPage(): void
    {
        // Arrange
        $productAbstractCollectionRefreshPluginMock = $this->createMock(ProductAbstractCollectionRefreshPluginInterface::class);
        $productAbstractPagePublisherMock = $this->createMock(ProductAbstractPagePublisherInterface::class);

        $productPageLoadTransfer = (new ProductPageLoadTransfer())->setProductAbstractIds([]);

        $productAbstractCollectionRefreshPluginMock
            ->expects($this->once())
            ->method('getProductPageLoadTransferForRefresh')
            ->willReturn($productPageLoadTransfer);

        $productAbstractPagePublisherMock->expects($this->once())
            ->method('publish')
            ->with($this->equalTo($productPageLoadTransfer->getProductAbstractIds()));

        /** @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $productPageSearchBusinessFactoryMock */
        $productPageSearchBusinessFactoryMock = $this->getMockBuilder(ProductPageSearchBusinessFactory::class)
            ->onlyMethods(['getProductPageRefreshPlugins', 'createProductAbstractPagePublisher'])
            ->getMock();

        $productPageSearchBusinessFactoryMock
            ->method('getProductPageRefreshPlugins')
            ->willReturn([$productAbstractCollectionRefreshPluginMock]);

        $productPageSearchBusinessFactoryMock
            ->method('createProductAbstractPagePublisher')
            ->willReturn($productAbstractPagePublisherMock);

        $this->productPageSearchFacade->setFactory($productPageSearchBusinessFactoryMock);

        // Act
        $this->productPageSearchFacade->refreshProductAbstractPage();
    }

    /**
     * @return void
     */
    public function testWriteProductConcretePageSearchCollectionByProductEventsWritesSearchDataForSearchableProduct(): void
    {
        // Arrange
        $productSearchEntity = $this->tester->getLocalizedProductSearchEntity();
        $eventEntityTransfer = (new EventEntityTransfer())->setId($productSearchEntity->getIdProductSearch());

        // Act
        $this->productPageSearchFacade->writeProductConcretePageSearchCollectionByProductEvents([$eventEntityTransfer]);
        $productConcretePageSearchEntity = $this->tester->findProductConcretePageSearchEntityByLocalizedProductSearchEntity($productSearchEntity);

        // Assert
        $this->assertNotNull($productConcretePageSearchEntity, 'ProductConcretePageSearchEntity should exist.');
    }

    /**
     * @return void
     */
    public function testWriteProductConcretePageSearchCollectionByProductEventsDoesntWriteSearchDataForNotSearchableProduct(): void
    {
        // Arrange
        $productSearchEntity = $this->tester->getLocalizedProductSearchEntity(false);
        $eventEntityTransfer = (new EventEntityTransfer())->setId($productSearchEntity->getIdProductSearch());

        // Act
        $this->productPageSearchFacade->writeProductConcretePageSearchCollectionByProductEvents([$eventEntityTransfer]);
        $productConcretePageSearchEntity = $this->tester->findProductConcretePageSearchEntityByLocalizedProductSearchEntity($productSearchEntity);

        // Assert
        $this->assertNull($productConcretePageSearchEntity, 'ProductConcretePageSearchEntity shouldn\'t exist.');
    }

    /**
     * @return array
     */
    protected function getDataWithDifferentParentCategories(): array
    {
        $productData = [
            'category_node_ids' => [14, 8],
            'all_parent_category_ids' => [14, 1, 5, 8],
            'boosted_category_names' => [
                14 => 'Variant Showcase',
                8 => 'Tablets',
            ],
            'category_names' => [
                5 => 'Computer',
            ],
            'sorted_categories' => [
                14 => [
                    'product_order' => 36,
                    'all_node_parents' => [14, 1],
                ],
                8 => [
                    'product_order' => 13,
                    'all_node_parents' => [5, 8, 1],
                ],
            ],
        ];

        $expectedPageMapTransfer = (new PageMapTransfer())
            ->setCategory((new CategoryMapTransfer())
                ->setAllParents($productData['all_parent_category_ids'])
                ->setDirectParents($productData['category_node_ids']))
            ->setFullText(['Computer'])
            ->setFullTextBoosted(['Variant Showcase', 'Tablets'])
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:14')
                ->setValue($productData['sorted_categories'][14]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:1')
                ->setValue($productData['sorted_categories'][14]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:8')
                ->setValue($productData['sorted_categories'][8]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:5')
                ->setValue($productData['sorted_categories'][8]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:1')
                ->setValue($productData['sorted_categories'][8]['product_order']));

        return [
            $productData,
            $expectedPageMapTransfer,
            'en_US',
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithParentCategoriesIntersected(): array
    {
        $productData = [
            'category_node_ids' => [18, 16],
            'all_parent_category_ids' => [18, 16, 3, 2, 1],
            'boosted_category_names' => [
                18 => 'Vegetables',
                16 => 'Food',
            ],
            'category_names' => [
                3 => 'Camcorders',
                2 => 'Cameras And Camcorder',
                1 => 'Demoshop',
            ],
            'sorted_categories' => [
                16 => [
                    'product_order' => 5,
                    'all_node_parents' => [16, 3, 1],
                ],
                18 => [
                    'product_order' => 24,
                    'all_node_parents' => [18, 16, 3, 2, 1],
                ],
            ],
        ];

        $expectedPageMapTransfer = (new PageMapTransfer())
            ->setCategory((new CategoryMapTransfer())
                ->setAllParents($productData['all_parent_category_ids'])
                ->setDirectParents($productData['category_node_ids']))
            ->setFullText(['Camcorders', 'Cameras And Camcorder', 'Demoshop'])
            ->setFullTextBoosted(['Vegetables', 'Food'])
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:16')
                ->setValue($productData['sorted_categories'][16]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:3')
                ->setValue($productData['sorted_categories'][16]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:1')
                ->setValue($productData['sorted_categories'][16]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:18')
                ->setValue($productData['sorted_categories'][18]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:2')
                ->setValue($productData['sorted_categories'][18]['product_order']));

        return [
            $productData,
            $expectedPageMapTransfer,
            'en_US',
        ];
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder
     */
    protected function createPageMapBuilder(): PageMapBuilder
    {
        return new PageMapBuilder();
    }
}
