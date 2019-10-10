<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentPreCheckPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group DummyPaymentPreCheckPluginTest
 * Add your own group annotations below this line
 */
class DummyPaymentPreCheckPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteShouldReturnCheckoutResponseTransfer()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $plugin = new DummyPaymentPreCheckPlugin();
        $result = $plugin->execute(new QuoteTransfer(), $checkoutResponseTransfer);

        $this->assertTrue($result);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }
}
