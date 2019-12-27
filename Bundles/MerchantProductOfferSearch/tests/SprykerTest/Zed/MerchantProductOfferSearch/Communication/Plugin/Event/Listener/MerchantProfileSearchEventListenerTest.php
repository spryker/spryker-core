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
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantProfileSearchEventListener;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;

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
 * @group MerchantProfileSearchEventListenerTest
 * Add your own group annotations below this line
 */
class MerchantProfileSearchEventListenerTest extends Unit
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
    public function testMerchantProfileSearchEventListenerStoreData(): void
    {
        // Arrange
        $beforeCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();

        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer, [MerchantProfileTransfer::IS_ACTIVE => true]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->addProductRelatedData($productConcreteTransfer);

        // Act
        $merchantProfileSearchEventListener = new MerchantProfileSearchEventListener();
        $merchantProfileSearchEventListener->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantProfileTransfer->getIdMerchantProfile()),
        ];
        $merchantProfileSearchEventListener->handleBulk($eventTransfers, MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_UPDATE);

        // Assert
        $afterCount = $this->tester->getProductAbstractPageSearchPropelQuery()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->tester->assertProductPageAbstractSearch($merchantTransfer, $productConcreteTransfer);
    }
}
