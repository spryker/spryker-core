<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchBusinessFactory;
use Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade;
use Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener\ProductReviewSearchListener;
use SprykerTest\Zed\ProductReviewSearch\ProductReviewSearchConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductReviewSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductReviewSearchListenerTest extends Unit
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
    public function testProductReviewSearchListenerStoreData()
    {
        SpyProductReviewSearchQuery::create()->filterByFkProductReview(1)->delete();
        $beforeCount = SpyProductReviewSearchQuery::create()->count();

        $productReviewSearchListener = new ProductReviewSearchListener();
        $productReviewSearchListener->setFacade($this->getProductReviewSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productReviewSearchListener->handleBulk($eventTransfers, ProductReviewEvents::PRODUCT_REVIEW_PUBLISH);

        // Assert
        $this->assertProductReviewSearch($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade
     */
    protected function getProductReviewSearchFacade()
    {
        $factory = new ProductReviewSearchBusinessFactory();
        $factory->setConfig(new ProductReviewSearchConfigMock());

        $facade = new ProductReviewSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductReviewSearch($beforeCount)
    {
        $productSetStorageCount = SpyProductReviewSearchQuery::create()->count();
        $this->assertSame($beforeCount + 1, $productSetStorageCount);
        $spyProductReviewSearch = SpyProductReviewSearchQuery::create()->orderByIdProductReviewSearch()->filterByFkProductReview(1)->findOne();
        $this->assertNotNull($spyProductReviewSearch);
        $data = json_decode($spyProductReviewSearch->getStructuredData(), true);
        $this->assertSame('Spencor', $data['nickname']);
    }
}
