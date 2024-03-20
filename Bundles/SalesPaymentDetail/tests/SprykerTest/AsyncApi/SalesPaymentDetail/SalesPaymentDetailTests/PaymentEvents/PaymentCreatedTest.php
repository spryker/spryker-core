<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\SalesPaymentDetail\SalesPaymentDetailTests\PaymentEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\AsyncApi\SalesPaymentDetail\SalesPaymentDetailAsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group SalesPaymentDetail
 * @group SalesPaymentDetailTests
 * @group PaymentEvents
 * @group PaymentCreatedTest
 * Add your own group annotations below this line
 */
class PaymentCreatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\SalesPaymentDetail\SalesPaymentDetailAsyncApiTester
     */
    protected SalesPaymentDetailAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testPaymentCreatedMessagePersistPaymentReferenceForTheOrderFoundForTheOrderReference(): void
    {
        // Arrange
        $paymentReference = Uuid::uuid4()->toString();

        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $paymentCreatedTransfer = $this->tester->havePaymentCreatedTransfer([
            PaymentCreatedTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            PaymentCreatedTransfer::PAYMENT_REFERENCE => $paymentReference,
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($paymentCreatedTransfer, 'payment-events');

        // Assert
        $salesPaymentDetailTransfer = new SalesPaymentDetailTransfer();
        $salesPaymentDetailTransfer
            ->setPaymentReference($paymentReference)
            ->setEntityReference($salesOrderEntity->getOrderReference());

        $this->tester->assertSalesPaymentDetailByPaymentReferenceIsIdentical($paymentReference, $salesPaymentDetailTransfer);
    }

    /**
     * @return void
     */
    public function testPaymentCreatedMessageIsIgnoredWhenPaymentReferenceForOrderReferenceAlreadyExists(): void
    {
        // Arrange
        $paymentReference = Uuid::uuid4()->toString();
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $salesPaymentDetailTransfer = $this->tester->haveSalesPaymentDetail([
            SalesPaymentDetailTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            SalesPaymentDetailTransfer::PAYMENT_REFERENCE => $paymentReference,
            SalesPaymentDetailTransfer::DETAILS => '{foo: bar}',
        ]);

        $paymentCreatedTransfer = $this->tester->havePaymentCreatedTransfer([
            PaymentCreatedTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            PaymentCreatedTransfer::PAYMENT_REFERENCE => $paymentReference,
            PaymentCreatedTransfer::DETAILS => '{foo: hasChanged}',
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($paymentCreatedTransfer, 'payment-events');

        // Assert
        $this->tester->assertSalesPaymentDetailByPaymentReferenceIsIdentical($paymentReference, $salesPaymentDetailTransfer);
    }
}
