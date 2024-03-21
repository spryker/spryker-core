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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
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
    use LocatorHelperTrait;

    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap::COL_FK_CATEGORY
     *
     * @var string
     */
    protected const COL_FK_CATEGORY = 'spy_category_store.fk_category';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

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
                $this->getLocatorHelper()->getLocator()->rabbitMq()->client()->createQueueAdapter(),
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
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('This test is not applicable for dynamic stores yet');
        }
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
            $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME])->getName(),
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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $localeTransfer = $this->tester->getLocaleFacade()->getCurrentLocale();
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
