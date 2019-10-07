<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentSaveOrderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group DummyPaymentSaveOrderPluginTest
 * Add your own group annotations below this line
 */
class DummyPaymentSaveOrderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteShouldReturnCheckoutResponseTransfer()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $plugin = new DummyPaymentSaveOrderPlugin();
        $checkoutResponseTransfer = $plugin->execute(new QuoteTransfer(), $checkoutResponseTransfer);

        $this->assertInstanceOf(CheckoutResponseTransfer::class, $checkoutResponseTransfer);
    }
}
