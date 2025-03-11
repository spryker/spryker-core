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
 * @group OmsFlowNewToClosedTest
 * Add your own group annotations below this line
 */
class OmsFlowNewToClosedTest extends Unit
{
    protected PaymentAppIntegrationTester $tester;

    /**
     * @return void
     */
    public function testMoveAnItemFromNewToClosed(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_NEW);
        $this->tester->receivePaymentAuthorizedMessage();

        $this->tester->tryToTransitionOrderItems();
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZED);

        $this->tester->tryToTransitionOrderItems(SalesPaymentHelper::EVENT_CAPTURE_PAYMENT);
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_CAPTURE_PENDING);

        $this->tester->receivePaymentCapturedMessage();

        $this->tester->tryToTransitionOrderItems();
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_CAPTURED);

        $this->tester->tryToTransitionOrderItems(SalesPaymentHelper::EVENT_PAYMENT_OVERPAY_CHECKED);
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_SENT_TO_MERCHANT);

        $this->tester->tryToTransitionOrderItems(SalesPaymentHelper::EVENT_DELIVER);
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_DELIVERED);

        $this->tester->tryToTransitionOrderItems(SalesPaymentHelper::EVENT_MERCHANT_PAYOUT);
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_CLOSED);
    }
}
