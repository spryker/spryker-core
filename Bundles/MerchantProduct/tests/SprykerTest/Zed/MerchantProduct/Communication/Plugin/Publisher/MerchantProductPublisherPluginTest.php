<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProduct\Communication\Plugin\Publisher\MerchantProductPublisherPlugin;
use SprykerTest\Zed\MerchantProduct\MerchantProductCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProduct
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group MerchantProductPublisherPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductPublisherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProduct\MerchantProductCommunicationTester
     */
    protected MerchantProductCommunicationTester $tester;

    /**
     * @return void
     */
    public function testMerchantSearchEventListenerStoresData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::IS_ACTIVE => true]);
        $this->tester->haveMerchantProduct([
            MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
        ]);
        $merchantSearchEventListener = new MerchantProductPublisherPlugin();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];

        // Assert
        $this->tester->setDependencyWithExpectedCall($productConcreteTransfer->getFkProductAbstract());

        // Act
        $merchantSearchEventListener->handleBulk($eventTransfers, MerchantEvents::MERCHANT_PUBLISH);
    }
}
