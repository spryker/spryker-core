<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Communication\Plugin\TaxApp;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp\MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin;
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
 * @group QuoteMerchantProfileExpanderPluginTest
 * Add your own group annotations below this line
 */
class QuoteMerchantProfileExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfile\MerchantProfileCommunicationTester
     */
    protected MerchantProfileCommunicationTester $tester;

    /**
     * @return void
     */
    public function testQuoteItemsAreExpandedWithMerchantProfileAddressWhenQuoteItemsHaveMerchantReference(): void
    {
        // Arrange
        $merchantTransfer1 = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchantTransfer1);
        $merchantTransfer2 = $this->tester->haveMerchant();
        $this->tester->haveMerchantProfile($merchantTransfer2);

        $quoteTransfer = $this->tester->haveQuoteTransfer([
            'items' => [
                [ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference()],
                [ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference()],
            ],
        ]);

        // Act
        $expandedQuoteTransfer = (new MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin())->expand($quoteTransfer);

        // Assert
        $this->tester->assertItemTransfersAreExpandedWithMerchantProfileAddress($expandedQuoteTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteItemsAreNotExpandedWithMerchantProfileAddressWhenQuoteItemsDontHaveMerchantReference(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->haveQuoteTransfer([
            'items' => [[]],
        ]);

        // Act
        $expandedQuoteTransfer = (new MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin())->expand($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expandedQuoteTransfer->getItems()->offsetGet(0);

        $merchantProfileAddressTransfer = $quoteItemTransfer->getMerchantProfileAddress();

        $this->assertNull($merchantProfileAddressTransfer);
    }
}
