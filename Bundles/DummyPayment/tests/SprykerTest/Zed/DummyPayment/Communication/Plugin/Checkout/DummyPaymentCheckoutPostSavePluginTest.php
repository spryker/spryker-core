<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConstants;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentCheckoutPostSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group DummyPaymentCheckoutPostSavePluginTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\DummyPayment\DummyPaymentCommunicationTester $tester
 */
class DummyPaymentCheckoutPostSavePluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteHookShouldReturnCheckoutResponseTransferWithoutAnyErrorIfPaymentAuthorizationApproved(): void
    {
        // Arrange
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $quoteTransfer = $this->tester->createQuoteTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress->setLastName('valid');
        $quoteTransfer->setBillingAddress($billingAddress);

        $plugin = new DummyPaymentCheckoutPostSavePlugin();

        // Act
        $plugin->executeHook($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testExecuteHookShouldReturnCheckoutResponseTransferWithErrorIfPaymentAuthorizationNotApproved(): void
    {
        // Arrange
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $quoteTransfer = $this->tester->createQuoteTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress->setLastName(DummyPaymentConstants::LAST_NAME_FOR_INVALID_TEST);
        $quoteTransfer->setBillingAddress($billingAddress);

        $plugin = new DummyPaymentCheckoutPostSavePlugin();

        // Act
        $plugin->executeHook($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }
}
