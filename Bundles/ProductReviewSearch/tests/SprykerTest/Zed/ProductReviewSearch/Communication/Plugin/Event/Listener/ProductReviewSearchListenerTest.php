<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchBusinessFactory;
use Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade;
use Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener\ProductReviewSearchListener;
use SprykerTest\Zed\ProductReviewSearch\ProductReviewSearchConfigMock;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\ProductReviewSearch\ProductReviewSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductReviewSearchListenerStoreData(): void
    {
        $beforeCount = SpyProductReviewSearchQuery::create()->count();

        $productReviewSearchListener = new ProductReviewSearchListener();
        $productReviewSearchListener->setFacade($this->getProductReviewSearchFacade());

        $customerTransfer = $this->tester->haveCustomer();
        $localeTransfer = $this->tester->haveLocale();
        $productAbstract = $this->tester->haveProductAbstract();

        $productReviewTransfer = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstract->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED,
            2,
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productReviewTransfer->getIdProductReview()),
        ];
        $productReviewSearchListener->handleBulk($eventTransfers, ProductReviewEvents::PRODUCT_REVIEW_PUBLISH);

        // Assert
        $productSetStorageCount = SpyProductReviewSearchQuery::create()->count();
        $this->assertEquals($beforeCount + 1, $productSetStorageCount);
        $spyProductReviewSearch = SpyProductReviewSearchQuery::create()
            ->orderByIdProductReviewSearch()
            ->filterByFkProductReview($productReviewTransfer->getIdProductReview())
            ->findOne();
        $this->assertNotNull($spyProductReviewSearch);
        $data = json_decode($spyProductReviewSearch->getStructuredData(), true);
        $this->assertSame($productReviewTransfer->getNickname(), $data['nickname']);
        $this->assertSame($productReviewTransfer->getIdProductReview(), $data['id_product_review']);
        $this->assertSame($productReviewTransfer->getFkLocale(), $data['fk_locale']);
        $this->assertSame($productReviewTransfer->getFkProductAbstract(), $data['fk_product_abstract']);
        $this->assertSame($productReviewTransfer->getCustomerReference(), $data['customer_reference']);
        $this->assertSame($productReviewTransfer->getDescription(), $data['description']);
        $this->assertSame($productReviewTransfer->getRating(), $data['rating']);
        $this->assertSame($productReviewTransfer->getSummary(), $data['summary']);
        $this->assertSame(
            $productReviewTransfer->getStatus(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED,
        );
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade
     */
    protected function getProductReviewSearchFacade(): ProductReviewSearchFacade
    {
        $factory = new ProductReviewSearchBusinessFactory();
        $factory->setConfig(new ProductReviewSearchConfigMock());

        $facade = new ProductReviewSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
