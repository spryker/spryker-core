<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConstants;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentPostCheckPlugin;

/**
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group DummyPaymentPostCheckPlugin
 */
class DummyPaymentPostCheckPluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testExecuteShouldReturnCheckoutResponseTransferWithoutAnyErrorIfPaymentAuthorizationApproved()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $quoteTransfer = new QuoteTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress->setLastName('valid');
        $quoteTransfer->setBillingAddress($billingAddress);

        $plugin = new DummyPaymentPostCheckPlugin();
        $checkoutResponseTransfer = $plugin->execute($quoteTransfer, $checkoutResponseTransfer);

        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testExecuteShouldReturnCheckoutResponseTransferWithErrorIfPaymentAuthorizationNotApproved()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $quoteTransfer = new QuoteTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress->setLastName(DummyPaymentConstants::LAST_NAME_FOR_INVALID_TEST);
        $quoteTransfer->setBillingAddress($billingAddress);

        $plugin = new DummyPaymentPostCheckPlugin();
        $checkoutResponseTransfer = $plugin->execute($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

}
