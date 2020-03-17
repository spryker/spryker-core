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
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationEvents(): void
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
            $this->tester->isProductRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationPublishingEvents(): void
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
            $this->tester->isProductRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationProductAbstractEvents(): void
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
            $this->tester->isProductRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductRelationStoreEvents(): void
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
            $this->tester->isProductRelationStorageRecordExists(
                $productRelationTransfer->getFkProductAbstract(),
                static::STORE_NAME
            ),
            'Product abstract relation storage record should exists'
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
