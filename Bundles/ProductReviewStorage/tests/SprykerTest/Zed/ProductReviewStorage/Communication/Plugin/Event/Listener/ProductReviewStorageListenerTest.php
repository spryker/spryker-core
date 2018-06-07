<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageBusinessFactory;
use Spryker\Zed\ProductReviewStorage\Business\ProductReviewStorageFacade;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewPublishStorageListener;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener\ProductReviewStorageListener;
use SprykerTest\Zed\ProductReviewStorage\ProductReviewStorageConfigMock;

/**
 * Auto-generated group annotations
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testProductReviewPublishStorageListenerStoreData()
    {
        SpyProductAbstractReviewStorageQuery::create()->filterByFkProductAbstract(86)->delete();
        $beforeCount = SpyProductAbstractReviewStorageQuery::create()->count();

        $productReviewPublishStorageListener = new ProductReviewPublishStorageListener();
        $productReviewPublishStorageListener->setFacade($this->getProductReviewStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(86),
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
        SpyProductAbstractReviewStorageQuery::create()->filterByFkProductAbstract(86)->delete();
        $beforeCount = SpyProductAbstractReviewStorageQuery::create()->count();

        $productReviewStorageListener = new ProductReviewStorageListener();
        $productReviewStorageListener->setFacade($this->getProductReviewStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT => 86,
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
        $this->assertSame($beforeCount + 1, $productSetStorageCount);
        $spyProductReviewStorage = SpyProductAbstractReviewStorageQuery::create()->orderByIdProductAbstractReviewStorage()->filterByFkProductAbstract(86)->findOne();
        $this->assertNotNull($spyProductReviewStorage);
        $data = $spyProductReviewStorage->getData();
        $this->assertSame(4, $data['review_count']);
    }
}
