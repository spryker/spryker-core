<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface;

class SalesOrderAmendmentQuoteCreator implements SalesOrderAmendmentQuoteCreatorInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Filter\QuoteFieldsFilterInterface $quoteFieldsFilter
     */
    public function __construct(
        protected SalesOrderAmendmentEntityManagerInterface $salesOrderAmendmentEntityManager,
        protected QuoteFieldsFilterInterface $quoteFieldsFilter
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function createSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer {
        $this->assertRequiredFields($salesOrderAmendmentQuoteCollectionRequestTransfer);

        $salesOrderAmendmentQuotesTransfers = $salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes();
        $persistedSalesOrderAmendmentQuoteTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderAmendmentQuotesTransfers) {
            return $this->executeCreateSalesOrderAmendmentQuoteTransaction($salesOrderAmendmentQuotesTransfers);
        });

        return (new SalesOrderAmendmentQuoteCollectionResponseTransfer())->setSalesOrderAmendmentQuotes($persistedSalesOrderAmendmentQuoteTransfers);
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer> $salesOrderAmendmentQuoteTransfers
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer>
     */
    protected function executeCreateSalesOrderAmendmentQuoteTransaction(ArrayObject $salesOrderAmendmentQuoteTransfers): ArrayObject
    {
        $persistedSalesOrderAmendmentQuoteTransfers = new ArrayObject();
        foreach ($salesOrderAmendmentQuoteTransfers as $entityIdentifier => $salesOrderAmendmentQuoteTransfer) {
            $quoteFieldsAllowedForSaving = $this->quoteFieldsFilter->filterQuoteFieldsAllowedForSaving(
                $salesOrderAmendmentQuoteTransfer->getQuoteOrFail(),
            );

            $persistedSalesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentEntityManager
                ->createSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer, $quoteFieldsAllowedForSaving);

            $persistedSalesOrderAmendmentQuoteTransfers->offsetSet($entityIdentifier, $persistedSalesOrderAmendmentQuoteTransfer);
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
                ->requireQuote()
                ->requireCustomerReference()
                ->requireAmendmentOrderReference();
        }
    }
}
