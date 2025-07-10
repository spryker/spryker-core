<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidatorInterface;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface;

class SalesOrderAmendmentQuoteUpdater implements SalesOrderAmendmentQuoteUpdaterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface $quoteFieldsFilter
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidatorInterface $salesOrderAmendmentQuoteValidator
     */
    public function __construct(
        protected SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager,
        protected QuoteFieldsFilterInterface $quoteFieldsFilter,
        protected SalesOrderAmendmentQuoteValidatorInterface $salesOrderAmendmentQuoteValidator
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function updateSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer {
        $this->assertRequiredFields($salesOrderAmendmentQuoteCollectionRequestTransfer);

        $salesOrderAmendmentQuoteTransfers = $salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes();

        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->salesOrderAmendmentQuoteValidator->validate($salesOrderAmendmentQuoteTransfers);
        if ($salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors()->count() > 0) {
            return $salesOrderAmendmentQuoteCollectionResponseTransfer;
        }

        $persistedSalesOrderAmendmentQuoteTransfers = $this->getTransactionHandler()->handleTransaction(
            fn () => $this->executeUpdateSalesOrderAmendmentQuoteCollectionTransaction($salesOrderAmendmentQuoteTransfers),
        );

        return $salesOrderAmendmentQuoteCollectionResponseTransfer
            ->setSalesOrderAmendmentQuotes($persistedSalesOrderAmendmentQuoteTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer> $salesOrderAmendmentQuoteTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer>
     */
    protected function executeUpdateSalesOrderAmendmentQuoteCollectionTransaction(ArrayObject $salesOrderAmendmentQuoteTransfers): ArrayObject
    {
        $persistedSalesOrderAmendmentQuoteTransfers = new ArrayObject();
        foreach ($salesOrderAmendmentQuoteTransfers as $salesOrderAmendmentQuoteTransfer) {
            $quoteFieldsAllowedForSaving = $this->quoteFieldsFilter->filterQuoteFieldsAllowedForSaving(
                $salesOrderAmendmentQuoteTransfer->getQuoteOrFail(),
            );

            $this->salesOrderAmendmentEntityManager->updateSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer, $quoteFieldsAllowedForSaving);
            $persistedSalesOrderAmendmentQuoteTransfers->append($salesOrderAmendmentQuoteTransfer);
        }

        return $persistedSalesOrderAmendmentQuoteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(
        SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
    ): void {
        $salesOrderAmendmentQuoteCollectionRequestTransfer
            ->requireSalesOrderAmendmentQuotes();

        foreach ($salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes() as $salesOrderAmendmentQuoteTransfer) {
            $salesOrderAmendmentQuoteTransfer
                ->requireIdSalesOrderAmendmentQuote()
                ->requireQuote()
                ->requireCustomerReference()
                ->requireAmendmentOrderReference();
        }
    }
}
