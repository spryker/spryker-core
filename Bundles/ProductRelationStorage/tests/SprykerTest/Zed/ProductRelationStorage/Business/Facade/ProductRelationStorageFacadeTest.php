<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationStoreTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelationStorage
 * @group Business
 * @group Facade
 * @group Facade
 * @group ProductRelationStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductRelationStorageFacadeTest extends Unit
{
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface
     */
    protected $productRelationStorageFacade;

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

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->productRelationStorageFacade = $this->tester->getFacade();
        $this->productRelationFacade = $this->tester->getLocator()->productRelation()->facade();
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationEventsShouldSaveProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $productRelationTransfer->getFkProductAbstract(),
            ]),
        ];

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationEvents($eventTransfers);

        // Assert
        $this->assertTrue(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationEventsShouldRemoveProductRelationFromProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();
        $idProductAbstract = $productRelationTransfer->getFkProductAbstract();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $idProductAbstract,
            ]),
        ];
        $this->productRelationStorageFacade->writeCollectionByProductRelationEvents($eventTransfers);
        $this->productRelationFacade->deleteProductRelation($productRelationTransfer->getIdProductRelation());

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationEvents($eventTransfers);

        // Assert
        $this->assertFalse(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should not exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationPublishingEventsShouldSaveProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productRelationTransfer->getFkProductAbstract()),
        ];

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationPublishingEvents($eventTransfers);

        // Assert
        $this->assertTrue(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationProductAbstractEventsShouldSaveProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $productRelationTransfer->getFkProductAbstract(),
            ]),
        ];

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationProductAbstractEvents($eventTransfers);

        // Assert
        $this->assertTrue(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationProductAbstractEventsShouldRemoveRelatedProductFromProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();
        $idProductAbstract = $productRelationTransfer->getFkProductAbstract();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $idProductAbstract,
            ]),
        ];
        $this->productRelationStorageFacade->writeCollectionByProductRelationProductAbstractEvents($eventTransfers);
        $productRelationTransfer->getQuerySet()->getRules()[0]->setValue('test');
        $this->productRelationFacade->updateProductRelation($productRelationTransfer);

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationProductAbstractEvents($eventTransfers);

        // Assert
        $this->assertFalse(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationStoreEventsShouldSaveProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationStoreTableMap::COL_FK_PRODUCT_RELATION => $productRelationTransfer->getIdProductRelation(),
            ]),
        ];

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationStoreEvents($eventTransfers);

        // Assert
        $this->assertTrue(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationStoreEventsShouldRemoveProductAbstractRelationStorage(): void
    {
        // Arrange
        $productRelationTransfer = $this->prepareProductRelation();

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationStoreTableMap::COL_FK_PRODUCT_RELATION => $productRelationTransfer->getIdProductRelation(),
            ]),
        ];
        $this->productRelationStorageFacade->writeCollectionByProductRelationStoreEvents($eventTransfers);
        SpyProductRelationStoreQuery::create()->filterByFkProductRelation($productRelationTransfer->getIdProductRelation())->delete();

        // Act
        $this->productRelationStorageFacade->writeCollectionByProductRelationStoreEvents($eventTransfers);

        // Assert
        $this->assertFalse(
            $this->tester->isProductAbstractRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should not exists'
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function prepareProductRelation(): ProductRelationTransfer
    {
        $this->tester->ensureProductRelationTableIsEmpty();
        $this->tester->ensureProductRelationStorageTableIsEmpty();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $relatedProductAbstract = $this->tester->haveProductAbstract();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME,
        ]);
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->addStores($storeTransfer)
            ->addIdStores($storeTransfer->getIdStore());

        return $this->tester->haveProductRelation(
            $relatedProductAbstract->getSku(),
            $productAbstractTransfer->getIdProductAbstract(),
            'test',
            'up-selling',
            $storeRelationTransfer
        );
    }
}
