<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business\Facade;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group Facade
 * @group WriteProductAbstractPageSearchCollectionByCategoryStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteProductAbstractPageSearchCollectionByCategoryStoreEventsTest extends Unit
{
    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap::COL_FK_CATEGORY
     *
     * @var string
     */
    protected const COL_FK_CATEGORY = 'spy_category_store.fk_category';

    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester
     */
    protected ProductPageSearchBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->setDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH, Stub::make(
            ProductPageSearchToSearchBridge::class,
            [
                'transformPageMapToDocumentByMapperName' => function () {
                    return [];
                },
            ],
        ));
    }

    /**
     * @return void
     */
    public function testWriteProductAbstractPageSearchCollectionByCategoryStoreEventsWritesDataByEvents(): void
    {
        // Arrange
        $categoryTransfer = $this->createCategory();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);

        // Act
        $this->tester->getFacade()->writeProductAbstractPageSearchCollectionByCategoryStoreEvents([$eventEntityTransfer]);

        // Assert
        $productPageSearchTransfer = $this->tester->findProductPageSearchTransfer(
            $this->tester->getProductAbstractTransfer()->getIdProductAbstractOrFail(),
            $this->tester->getStoreFacade()->getCurrentStore()->getNameOrFail(),
        );

        $this->assertNotNull($productPageSearchTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesProductPageSearchCollectionFilterPlugins(): void
    {
        // Arrange
        $categoryTransfer = $this->createCategory();
        $eventEntityTransfer = (new EventEntityTransfer())->setForeignKeys([
            static::COL_FK_CATEGORY => $categoryTransfer->getIdCategory(),
        ]);
        $productPageSearchCollectionFilterPluginMock = $this->createProductPageSearchCollectionFilterPluginMock();
        $this->tester->setDependency(
            ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_PAGE_SEARCH_COLLECTION_FILTER,
            [$productPageSearchCollectionFilterPluginMock],
        );

        // Assert
        $productPageSearchCollectionFilterPluginMock->expects($this->once())->method('filter')->willReturn([]);

        // Act
        $this->tester->getFacade()->writeProductAbstractPageSearchCollectionByCategoryStoreEvents([$eventEntityTransfer]);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategory(): CategoryTransfer
    {
        $localeTransfer = $this->tester->getLocaleFacade()->getCurrentLocale();
        $storeTransfer = $this->tester->getStoreFacade()->getCurrentStore();
        $categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $localeTransfer]);
        $this->tester->haveCategoryStoreRelation(
            $categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore(),
        );
        $this->tester->assignProductToCategory(
            $categoryTransfer->getIdCategory(),
            $this->tester->getProductAbstractTransfer()->getIdProductAbstractOrFail(),
        );

        return $categoryTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface
     */
    protected function createProductPageSearchCollectionFilterPluginMock(): ProductPageSearchCollectionFilterPluginInterface
    {
        return $this->getMockBuilder(ProductPageSearchCollectionFilterPluginInterface::class)->getMock();
    }
}
