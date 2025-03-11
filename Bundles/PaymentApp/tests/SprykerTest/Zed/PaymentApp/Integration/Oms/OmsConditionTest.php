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
 * @group OmsConditionTest
 * Add your own group annotations below this line
 */
class OmsConditionTest extends Unit
{
    protected PaymentAppIntegrationTester $tester;

    /**
     * @return void
     */
    public function testItemInStatePaymentPendingStaysInPaymentPendingWhenThePaymentAppStatusIsNotSet(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_PAYMENT_PENDING);

        // Act
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_PENDING);
    }

    /**
     * @return void
     */
    public function testItemInStatePaymentPendingIsTransitionedToPaymentAuthorizationFailedWhenThePaymentAppStatusIsAuthorizationFailed(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_PAYMENT_PENDING);
        $this->tester->receivePaymentAuthorizationFailedMessage();

        // Act
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZATION_FAILED);
    }

    /**
     * When the PaymentApp moves faster than the OMS it must be possible to transition the item to the next state.
     *
     * @return void
     */
    public function testItemInStatePaymentPendingIsTransitionedToPaymentAuthorizedWhenThePaymentAppStatusIsCaptured(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_PAYMENT_PENDING);
        $this->tester->receivePaymentAuthorizedMessage();
        $this->tester->receivePaymentCapturedMessage();

        // Act
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZED);
    }

    /**
     * @return void
     */
    public function testItemInStatePaymentAuthorizationFailedIsTransitionedToPaymentAuthorizedWhenThePaymentAppStatusIsAuthorized(): void
    {
        // Arrange
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZATION_FAILED);
        $this->tester->receivePaymentAuthorizedMessage();

        // Act
        $this->tester->tryToTransitionOrderItems();

        // Assert
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZED);
    }
}
