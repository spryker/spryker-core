<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\TaxApp\TaxAppTests\TaxCommands;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\SubmitPaymentTaxInvoiceTransfer;
use SprykerTest\AsyncApi\TaxApp\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group TaxApp
 * @group TaxAppTests
 * @group TaxCommands
 * @group SubmitPaymentTaxInvoiceTest
 * Add your own group annotations below this line
 */
class SubmitPaymentTaxInvoiceTest extends Unit
{
    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\AsyncApi\TaxApp\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureTaxAppConfigTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testSubmitPaymentTaxInvoiceWhenStoreReferenceIsProvidedThenMessageIsSent(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $orderTransfer = $this->tester->getOrderTransferForSubmitPaymentTaxInvoice();
        $this->tester->mockSalesFacadeFindOrderByIdSalesOrderMethod($orderTransfer);

        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $orderTransfer->getCreatedAt());

        $taxAppSaleTransfer = $this->tester->haveTaxAppSaleTransfer([
            'transaction_id' => $orderTransfer->getOrderReference(),
            'document_number' => $orderTransfer->getOrderReference(),
            'document_date' => $createdAt->format('Y-m-d'),
        ]);

        $submitPaymentTaxInvoiceTransfer = (new SubmitPaymentTaxInvoiceTransfer())
            ->setSale($taxAppSaleTransfer);

        // Act
        $this->tester->getFacade()->sendSubmitPaymentTaxInvoiceMessage($orderTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($submitPaymentTaxInvoiceTransfer, 'payment-tax-invoice-commands', function (SubmitPaymentTaxInvoiceTransfer $expectedMessageTransfer, SubmitPaymentTaxInvoiceTransfer $sentMessageTransfer): void {
            $this->assertSame($expectedMessageTransfer->getSale()->getTransactionId(), $sentMessageTransfer->getSale()->getTransactionId());
            $this->assertSame($expectedMessageTransfer->getSale()->getDocumentNumber(), $sentMessageTransfer->getSale()->getDocumentNumber());
            $this->assertSame($expectedMessageTransfer->getSale()->getDocumentDate(), $sentMessageTransfer->getSale()->getDocumentDate());
        });
    }
}
