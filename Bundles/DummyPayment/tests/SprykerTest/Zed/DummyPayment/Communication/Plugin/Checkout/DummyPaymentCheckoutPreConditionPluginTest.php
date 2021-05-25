<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentCheckoutPreConditionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group DummyPaymentCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\DummyPayment\DummyPaymentCommunicationTester $tester
 */
class DummyPaymentCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testCheckConditionResultShouldBeSuccess(): void
    {
        // Arrange
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $plugin = new DummyPaymentCheckoutPreConditionPlugin();

        // Act
        $result = $plugin->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }
}
