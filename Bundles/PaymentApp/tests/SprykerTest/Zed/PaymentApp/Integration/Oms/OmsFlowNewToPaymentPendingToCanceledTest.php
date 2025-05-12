<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Integration\Oms;

use Codeception\Test\Unit;
use SprykerTest\Zed\PaymentApp\PaymentAppIntegrationTester;
use SprykerTest\Zed\SalesPayment\Helper\SalesPaymentHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Integration
 * @group Oms
 * @group OmsFlowNewToPaymentPendingToCanceledTest
 * Add your own group annotations below this line
 */
class OmsFlowNewToPaymentPendingToCanceledTest extends Unit
{
    protected PaymentAppIntegrationTester $tester;

    /**
     * Covers the case, when a not logged in customer cancel an order on the hosted payment page with PayPal.
     * PayOne sends a failed notification with code 970 and the order item should be moved to the canceled state.
     *
     * @return void
     */
    public function testMoveAnItemFromNewToPaymentPendingToCanceled(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_NEW);

        // Act
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_PENDING);

        // Act
        $this->tester->receivePaymentCanceledMessage();
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_CANCELLED);
    }
}
