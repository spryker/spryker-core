<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Replacer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentConditionsTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesPayment\Business\Deleter\SalesPaymentDeleterInterface;
use Spryker\Zed\SalesPayment\Business\Reader\SalesPaymentReaderInterface;
use Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface;

class SalesPaymentReplacer implements SalesPaymentReplacerInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesPayment\Business\Reader\SalesPaymentReaderInterface $salesPaymentReader
     * @param \Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface $salesPaymentWriter
     * @param \Spryker\Zed\SalesPayment\Business\Deleter\SalesPaymentDeleterInterface $salesPaymentDeleter
     */
    public function __construct(
        protected SalesPaymentReaderInterface $salesPaymentReader,
        protected SalesPaymentWriterInterface $salesPaymentWriter,
        protected SalesPaymentDeleterInterface $salesPaymentDeleter
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer): void {
            $this->executeReplaceSalesPaymentsTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeReplaceSalesPaymentsTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesPaymentCriteriaTransfer = (new SalesPaymentCriteriaTransfer())->setSalesPaymentConditions(
            (new SalesPaymentConditionsTransfer())->addIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail()),
        );
        $salesPaymentCollectionTransfer = $this->salesPaymentReader->getSalesPaymentCollection($salesPaymentCriteriaTransfer);

        $this->salesPaymentDeleter->deleteSalesPayments($salesPaymentCollectionTransfer);
        $this->salesPaymentWriter->saveOrderPayments($quoteTransfer, $saveOrderTransfer);
    }
}
