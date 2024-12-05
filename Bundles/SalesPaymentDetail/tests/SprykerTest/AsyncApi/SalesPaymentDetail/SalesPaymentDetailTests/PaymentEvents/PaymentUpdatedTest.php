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
 * @group PaymentUpdatedTest
 * Add your own group annotations below this line
 */
class PaymentUpdatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\SalesPaymentDetail\SalesPaymentDetailAsyncApiTester
     */
    protected SalesPaymentDetailAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenSalesPaymentDetailsDoNotExistsWhenThePaymentUpdatedMessageHasAnEntityReferenceAndAPaymentReferenceThenTheSalesPaymentDetailsArePersisted(): void
    {
        // Arrange
        $paymentReference = Uuid::uuid4()->toString();

        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $paymentUpdatedTransfer = $this->tester->havePaymentUpdatedTransfer([
            PaymentCreatedTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            PaymentCreatedTransfer::PAYMENT_REFERENCE => $paymentReference,
            PaymentCreatedTransfer::DETAILS => '{"test": "value"}',
        ]);

        $salesPaymentDetailTransfer = new SalesPaymentDetailTransfer();
        $salesPaymentDetailTransfer->fromArray($paymentUpdatedTransfer->toArray(), true);

        // Act: This will trigger the MessageHandlerPlugin for this message.
        $this->tester->runMessageReceiveTest($paymentUpdatedTransfer, 'payment-events');

        $this->tester->assertSalesPaymentDetailByPaymentReferenceIsIdentical($paymentReference, $salesPaymentDetailTransfer);
    }

    /**
     * @return void
     */
    public function testGivenSalesPaymentDetailsExistsForAnOrderReferenceAndPaymentReferenceWhenThePaymentUpdatedMessageHasUpdatedDetailsThenTheNewDetailsArePersisted(): void
    {
        // Arrange
        $paymentReference = Uuid::uuid4()->toString();

        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $salesPaymentDetailTransfer = $this->tester->haveSalesPaymentDetail([
            SalesPaymentDetailTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            SalesPaymentDetailTransfer::PAYMENT_REFERENCE => $paymentReference,
            SalesPaymentDetailTransfer::DETAILS => '{"foo": "bar"}',
        ]);

        // Change the data to be what is expected after the message is processed.
        $salesPaymentDetailTransfer->setDetails('{"foo": "hasChanged"}');

        $paymentUpdatedTransfer = $this->tester->havePaymentUpdatedTransfer([
            PaymentCreatedTransfer::ENTITY_REFERENCE => $salesOrderEntity->getOrderReference(),
            PaymentCreatedTransfer::PAYMENT_REFERENCE => $paymentReference,
            PaymentCreatedTransfer::DETAILS => '{"foo": "hasChanged"}',
        ]);

        // Act: This will trigger the MessageHandlerPlugin for this message and should update the existing sales payment detail.
        $this->tester->runMessageReceiveTest($paymentUpdatedTransfer, 'payment-events');

        // Assert
        $this->tester->assertSalesPaymentDetailByPaymentReferenceIsIdentical($paymentReference, $salesPaymentDetailTransfer);
    }
}
