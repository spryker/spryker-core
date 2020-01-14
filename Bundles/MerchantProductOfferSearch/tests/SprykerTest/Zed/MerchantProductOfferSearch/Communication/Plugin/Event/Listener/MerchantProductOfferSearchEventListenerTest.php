<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantProductOfferSearchEventListener;

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
 * @group MerchantProductOfferSearchEventListenerTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferSearchEventListenerTest extends Unit
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
    public function testMerchantProductOfferSearchEventListenerStoreData(): void
    {
        // Arrange
        $beforeCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();

        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchantTransfer, [MerchantProfileTransfer::IS_ACTIVE => true]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->addProductRelatedData($productConcreteTransfer);

        // Act
        $merchantProductOfferSearchEventListener = new MerchantProductOfferSearchEventListener();
        $merchantProductOfferSearchEventListener->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($productOfferTransfer->getIdProductOffer()),
        ];
        $merchantProductOfferSearchEventListener->handleBulk($eventTransfers, MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_UPDATE);
        $afterCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();

        // Assert
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->tester->assertProductPageAbstractSearch($merchantTransfer, $productConcreteTransfer);
    }
}
