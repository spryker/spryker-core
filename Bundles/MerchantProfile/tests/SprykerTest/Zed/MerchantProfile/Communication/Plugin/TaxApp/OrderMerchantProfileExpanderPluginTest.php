<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Communication\Plugin\TaxApp;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp\MerchantProfileAddressOrderTaxAppExpanderPlugin;
use SprykerTest\Zed\MerchantProfile\MerchantProfileCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfile
 * @group Communication
 * @group Plugin
 * @group TaxApp
 * @group OrderMerchantProfileExpanderPluginTest
 * Add your own group annotations below this line
 */
class OrderMerchantProfileExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfile\MerchantProfileCommunicationTester
     */
    protected MerchantProfileCommunicationTester $tester;

    /**
     * @return void
     */
    public function testOrderItemsAreExpandedWithMerchantProfileAddressWhenQuoteItemsHaveMerchantReference(): void
    {
        // Arrange
        $merchantTransfer1 = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchantTransfer1);
        $merchantTransfer2 = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchantTransfer2);

        $orderTransfer = $this->tester->haveOrderTransfer([
            'items' => [
                [ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference()],
                [ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference()],
            ],
        ]);

        // Act
        $expandedOrderTransfer = (new MerchantProfileAddressOrderTaxAppExpanderPlugin())->expand($orderTransfer);

        // Assert
        $this->tester->assertItemTransfersAreExpandedWithMerchantProfileAddress($expandedOrderTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteItemsAreNotExpandedWithMerchantProfileAddressWhenQuoteItemsDontHaveMerchantReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->haveOrderTransfer([
            'items' => [[]],
        ]);

        // Act
        $expandedQuoteTransfer = (new MerchantProfileAddressOrderTaxAppExpanderPlugin())->expand($orderTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        $orderItemTransfer = $expandedQuoteTransfer->getItems()->offsetGet(0);

        $merchantProfileAddressTransfer = $orderItemTransfer->getMerchantProfileAddress();
        $this->assertNull($merchantProfileAddressTransfer);
    }
}
