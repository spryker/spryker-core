<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group AbstractStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class AbstractStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester
     */
    protected $tester;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMerchantProductOfferStorageToEventBehaviorFacadeInterfaceMock(
        ProductOfferTransfer $productOfferTransfer
    ): MerchantProductOfferStorageToEventBehaviorFacadeInterface {
        $eventBehaviorFacade = $this->getMockBuilder(MerchantProductOfferStorageToEventBehaviorFacadeInterface::class)->getMock();
        $eventBehaviorFacade->method('getEventTransfersAdditionalValues')->willReturn([$productOfferTransfer->getProductOfferReference()]);

        return $eventBehaviorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMerchantProductOfferStorageRepositoryMock(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): MerchantProductOfferStorageRepositoryInterface {
        $productOfferFacade = $this->getMockBuilder(MerchantProductOfferStorageRepositoryInterface::class)->getMock();
        $productOfferFacade->method('getProductOffers')->willReturn($productOfferCollectionTransfer);

        return $productOfferFacade;
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMerchantProductOfferStorageToStoreFacadeInterfaceMock(): MerchantProductOfferStorageToStoreFacadeInterface
    {
        $storeFacade = $this->getMockBuilder(MerchantProductOfferStorageToStoreFacadeInterface::class)->getMock();
        $storeFacade->method('getAllStores')->willReturn($this->tester->getAllStoreTransfers());

        return $storeFacade;
    }
}
