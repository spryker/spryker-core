<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentOverpaidTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group PaymentOverpaidMessageTest
 * Add your own group annotations below this line
 */
class PaymentOverpaidMessageTest extends Unit
{
    protected PaymentAppCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGivenNoStatusEntityForAPaymentExistsWhenThePaymentOverpaidMessageIsHandledThenAStatusEntityIsCreated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentOverpaidMessage([PaymentOverpaidTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusEntityExists($orderReference, PaymentStatus::STATUS_OVERPAID);
    }

    /**
     * @return void
     */
    public function testGivenAStatusEntityForAPaymentExistsWhenThePaymentOverpaidMessageIsHandledThenTheStatusEntityIsUpdated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentAuthorizedMessage([PaymentAuthorizedTransfer::ORDER_REFERENCE => $orderReference]);
        $this->tester->receivePaymentOverpaidMessage([PaymentOverpaidTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusEntityExists($orderReference, PaymentStatus::STATUS_OVERPAID);
    }

    /**
     * @return void
     */
    public function testGivenNoStatusEntityForAPaymentExistsWhenThePaymentOverpaidMessageIsHandledThenAStatusHistoryEntityIsCreated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentOverpaidMessage([PaymentOverpaidTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusHistoryEntityExists($orderReference, PaymentStatus::STATUS_OVERPAID);
    }
}
