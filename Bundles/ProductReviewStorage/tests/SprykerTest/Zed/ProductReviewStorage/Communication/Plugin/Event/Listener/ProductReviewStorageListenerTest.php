<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery;
use Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageBusinessFactory;
use Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageFacade;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewPublishStorageListener;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewStorageListener;
use SprykerTest\Zed\ProductReviewStorage\ProductReviewStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductReviewStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductReviewStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductReviewStorage\ProductReviewStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected $productReviewTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $productReviewTransfer = $this->tester->haveProductReview([ProductReviewTransfer::STATUS => SpyProductReviewTableMap::COL_STATUS_APPROVED]);
        $productReviewTransfer = $this->getProductReviewFacade()->createProductReview($productReviewTransfer);

        $productReviewTransferToUpdate = (new ProductReviewBuilder([
            ProductReviewTransfer::ID_PRODUCT_REVIEW => $productReviewTransfer->getIdProductReview(),
            ProductReviewTransfer::STATUS => SpyProductReviewTableMap::COL_STATUS_APPROVED,
        ]))->build();

        // Act
        $this->productReviewTransfer = $this->getProductReviewFacade()->updateProductReviewStatus($productReviewTransferToUpdate);
    }

    /**
     * @return void
     */
    public function testProductReviewPublishStorageListenerStoreData()
    {
        SpyProductAbstractReviewStorageQuery::create()->filterByFkProductAbstract($this->productReviewTransfer->getFkProductAbstract())->delete();
        $beforeCount = SpyProductAbstractReviewStorageQuery::create()->count();

        $productReviewPublishStorageListener = new ProductReviewPublishStorageListener();
        $productReviewPublishStorageListener->setFacade($this->getProductReviewStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productReviewTransfer->getFkProductAbstract()),
        ];
        $productReviewPublishStorageListener->handleBulk($eventTransfers, ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_PUBLISH);

        // Assert
        $this->assertProductReviewStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductReviewStorageListenerStoreData()
    {
        SpyProductAbstractReviewStorageQuery::create()->filterByFkProductAbstract($this->productReviewTransfer->getFkProductAbstract())->delete();
        $beforeCount = SpyProductAbstractReviewStorageQuery::create()->count();

        $productReviewStorageListener = new ProductReviewStorageListener();
        $productReviewStorageListener->setFacade($this->getProductReviewStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productReviewTransfer->getFkProductAbstract(),
            ]),
        ];
        $productReviewStorageListener->handleBulk($eventTransfers, ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE);

        // Assert
        $this->assertProductReviewStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageFacade
     */
    protected function getProductReviewStorageFacade()
    {
        $factory = new ProductReviewStorageBusinessFactory();
        $factory->setConfig(new ProductReviewStorageConfigMock());

        $facade = new ProductReviewStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductReviewStorage($beforeCount)
    {
        $productSetStorageCount = SpyProductAbstractReviewStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productSetStorageCount);
        $spyProductReviewStorage = SpyProductAbstractReviewStorageQuery::create()->orderByIdProductAbstractReviewStorage()->filterByFkProductAbstract($this->productReviewTransfer->getFkProductAbstract())->findOne();
        $this->assertNotNull($spyProductReviewStorage);
        $data = $spyProductReviewStorage->getData();
        $this->assertSame(1, (int)$data['review_count']);
    }

    /**
     * @return \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface
     */
    protected function getProductReviewFacade(): ProductReviewFacadeInterface
    {
        return $this->tester->getLocator()->productReview()->facade();
    }
}
