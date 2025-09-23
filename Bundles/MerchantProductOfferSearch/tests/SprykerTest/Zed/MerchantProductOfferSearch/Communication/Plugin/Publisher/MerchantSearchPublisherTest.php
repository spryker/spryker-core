<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantSearchEventListener;
use SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferSearch
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group MerchantSearchPublisherTest
 * Add your own group annotations below this line
 */
class MerchantSearchPublisherTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected MerchantProductOfferSearchCommunicationTester $tester;

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
    public function testListenMerchantEventAndRepublishAbstractProducts(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->addProductRelatedData($productConcreteTransfer);
        $merchantSearchEventListener = new MerchantSearchEventListener();
        $merchantSearchEventListener->setFacade($this->tester->getFacade());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        // Assert
        $this->tester->setDependencyWithExpectedCall($productConcreteTransfer->getFkProductAbstract());

        // Act
        $merchantSearchEventListener->handleBulk($eventTransfers, MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE);
    }
}
