<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Persistence;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

interface SalesOrderAmendmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function createSalesOrderAmendment(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     * @param array<string|array<string>> $quoteFieldsAllowedForSaving
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer
     */
    public function createSalesOrderAmendmentQuote(
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer,
        array $quoteFieldsAllowedForSaving
    ): SalesOrderAmendmentQuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function updateSalesOrderAmendment(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return void
     */
    public function deleteSalesOrderAmendment(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): void;

    /**
     * @param list<int> $salesOrderAmendmentQuoteIds
     *
     * @return void
     */
    public function deleteSalesOrderAmendmentQuotes(
        array $salesOrderAmendmentQuoteIds
    ): void;
}
