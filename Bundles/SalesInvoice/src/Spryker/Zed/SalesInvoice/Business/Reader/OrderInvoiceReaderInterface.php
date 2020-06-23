<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Reader;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderInvoiceReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    public function getOrderInvoices(OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer): OrderInvoiceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function expandOrderInvoiceWithRenderedInvoice(
        OrderInvoiceTransfer $orderInvoiceTransfer,
        OrderTransfer $orderTransfer
    ): OrderInvoiceTransfer;
}
