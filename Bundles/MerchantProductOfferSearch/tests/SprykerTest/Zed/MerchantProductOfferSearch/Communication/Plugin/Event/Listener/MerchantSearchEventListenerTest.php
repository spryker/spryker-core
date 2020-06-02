<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantSearchEventListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantSearchEventListenerTest
 * Add your own group annotations below this line
 */
class MerchantSearchEventListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testMerchantSearchEventListenerStoresData(): void
    {
        // Arrange
        $beforeCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();

        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->addProductRelatedData($productConcreteTransfer);
        $merchantSearchEventListener = new MerchantSearchEventListener();
        $merchantSearchEventListener->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        // Act
        $merchantSearchEventListener->handleBulk($eventTransfers, MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE);
        $afterCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();

        // Assert
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->tester->assertProductPageAbstractSearch($merchantTransfer, $productConcreteTransfer);
    }
}
