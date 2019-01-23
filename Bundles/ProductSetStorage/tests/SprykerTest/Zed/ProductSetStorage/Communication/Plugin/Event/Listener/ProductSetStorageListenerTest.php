<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorageQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetStorage\Business\ProductSetStorageBusinessFactory;
use Spryker\Zed\ProductSetStorage\Business\ProductSetStorageFacade;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductAbstractProductSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetDataStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageSetImageStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetStoragePublishListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetStorageUnpublishListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetUrlStorageListener;
use Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainer;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductSetStorage\ProductSetStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductSetStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductSetStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductSetStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductSetStorage\ProductSetStorageCommunicationTester
     */
    protected $tester;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }
    }

    /**
     * @return void
     */
    public function testProductSetStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productSetStorageListener = new ProductSetStorageListener();
        $productSetStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $productSetStorageListener->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_PUBLISH);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetStoragePublishListener(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productSetStoragePublishListener = new ProductSetStoragePublishListener();
        $productSetStoragePublishListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $productSetStoragePublishListener->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_PUBLISH);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetStorageUnpublishListener(): void
    {
        // Prepare
        $productSetStorageUnpublishListener = new ProductSetStorageUnpublishListener();
        $productSetStorageUnpublishListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];

        // Act
        $productSetStorageUnpublishListener->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->count());
    }

    /**
     * @return void
     */
    public function testProductAbstractProductSetStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productAbstractProductSetStorageListener = new ProductAbstractProductSetStorageListener();
        $productAbstractProductSetStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET => 1,
            ]),
        ];

        // Act
        $productAbstractProductSetStorageListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetDataStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productSetDataStorageListener = new ProductSetDataStorageListener();
        $productSetDataStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET => 1,
            ]),
        ];

        // Act
        $productSetDataStorageListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageStorageListenerStoreData(): void
    {
        // Prepare
        $queryContainer = new ProductSetStorageQueryContainer();
        $productSetIds = $queryContainer->queryProductSetIdsByProductImageIds([208])->find()->getData();
        SpyProductSetStorageQuery::create()->filterByFkProductSet_In($productSetIds)->delete();
        $beforeCount = SpyProductSetStorageQuery::create()->count();

        $productSetProductImageStorageListener = new ProductSetProductImageStorageListener();
        $productSetProductImageStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(208),
        ];

        // Act
        $productSetProductImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $this->assertGreaterThanOrEqual($beforeCount, $productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageSetStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productSetProductImageSetStorageListener = new ProductSetProductImageSetStorageListener();
        $productSetProductImageSetStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET => 1,
            ]),
        ];

        // Act
        $productSetProductImageSetStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetProductImageSetImageStorageListenerStoreData(): void
    {
        // Prepare
        $queryContainer = new ProductSetStorageQueryContainer();
        $productSetIds = $queryContainer->queryProductSetIdsByProductImageSetToProductImageIds([1021])->find()->getData();
        SpyProductSetStorageQuery::create()->filterByFkProductSet_In($productSetIds)->delete();
        $beforeCount = SpyProductSetStorageQuery::create()->count();

        $productSetProductImageSetImageStorageListener = new ProductSetProductImageSetImageStorageListener();
        $productSetProductImageSetImageStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1021),
        ];

        // Act
        $productSetProductImageSetImageStorageListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $productSetStorageCount);
    }

    /**
     * @return void
     */
    public function testProductSetUrlStorageListenerStoreData(): void
    {
        // Prepare
        SpyProductSetStorageQuery::create()->filterByFkProductSet(1)->delete();
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $productSetUrlStorageListener = new ProductSetUrlStorageListener();
        $productSetUrlStorageListener->setFacade($this->getProductSetStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET => 1,
            ])
            ->setModifiedColumns([
                SpyUrlTableMap::COL_URL,
            ]),
        ];

        // Act
        $productSetUrlStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $this->assertProductSetStorage($productSetStorageCount);
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Business\ProductSetStorageFacade
     */
    protected function getProductSetStorageFacade()
    {
        $factory = new ProductSetStorageBusinessFactory();
        $factory->setConfig(new ProductSetStorageConfigMock());

        $facade = new ProductSetStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductSetStorage(int $beforeCount): void
    {
        $productSetStorageCount = SpyProductSetStorageQuery::create()->count();

        $this->assertGreaterThanOrEqual($beforeCount, $productSetStorageCount);
        $spyProductSetStorage = SpyProductSetStorageQuery::create()->orderByFkProductSet()->filterByFkProductSet(1)->findOne();
        $this->assertNotNull($spyProductSetStorage);
        $data = $spyProductSetStorage->getData();
        $this->assertSame('HP Product Set', $data['name']);
    }
}
