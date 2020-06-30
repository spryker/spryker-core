<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceResponseTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesInvoiceFacadeInterface
{
    /**
     * Specification:
     * - Generates order invoice with the currently configured template path if it does not exist.
     * - Executes OrderInvoiceBeforeSavePluginInterface plugins before storing it into the persistence.
     * - Requires OrderTransfer::ID_SALES_ORDER to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceResponseTransfer
     */
    public function generateOrderInvoice(OrderTransfer $orderTransfer): OrderInvoiceResponseTransfer;

    /**
     * Specification:
     * - Loads order invoices according to provided criteria.
     * - Executes OrderInvoicesExpanderPluginInterface plugins.
     * - Populates raw format invoices into results when OrderInvoiceCriteriaTransfer::expandWithRawInvoice is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    public function getOrderInvoices(OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer): OrderInvoiceCollectionTransfer;

    /**
     * Specification:
     * - Sends invoice emails with "email_sent=false" in batches.
     * - Executes OrderInvoicesExpanderPluginInterface plugins.
     * - Sets "email_sent=true" after sending.
     * - Requires batch size to be provided.
     * - Ignores "email_sent" flag in case "forced=true" option is provided.
     * - Considers "salesOrderIds" option when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer
     */
    public function sendOrderInvoices(OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer): OrderInvoiceSendResponseTransfer;
}
