<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentCanceledTransfer;
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
 * @group PaymentCanceledMessageTest
 * Add your own group annotations below this line
 */
class PaymentCanceledMessageTest extends Unit
{
    protected PaymentAppCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGivenNoStatusEntityForAPaymentExistsWhenThePaymentCanceledMessageIsHandledThenAStatusEntityIsCreated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentCanceledMessage([PaymentCanceledTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusEntityExists($orderReference, PaymentStatus::STATUS_CANCELED);
    }

    /**
     * @return void
     */
    public function testGivenAStatusEntityForAPaymentExistsWhenThePaymentCanceledMessageIsHandledThenTheStatusEntityIsUpdated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentAuthorizedMessage([PaymentAuthorizedTransfer::ORDER_REFERENCE => $orderReference]);
        $this->tester->receivePaymentCanceledMessage([PaymentCanceledTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusEntityExists($orderReference, PaymentStatus::STATUS_CANCELED);
    }

    /**
     * @return void
     */
    public function testGivenNoStatusEntityForAPaymentExistsWhenThePaymentCanceledMessageIsHandledThenAStatusHistoryEntityIsCreated(): void
    {
        // Arrange
        $orderReference = Uuid::uuid4()->toString();

        // Act
        $this->tester->receivePaymentCanceledMessage([PaymentCanceledTransfer::ORDER_REFERENCE => $orderReference]);

        // Assert
        $this->tester->assertPaymentAppPaymentStatusHistoryEntityExists($orderReference, PaymentStatus::STATUS_CANCELED);
    }
}
