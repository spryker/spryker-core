<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Persistence;

use Generated\Shared\Transfer\OrderInvoiceTransfer;

interface SalesInvoiceEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function createOrderInvoice(OrderInvoiceTransfer $orderInvoiceTransfer): OrderInvoiceTransfer;

    /**
     * @param int[] $orderInvoiceIds
     *
     * @return void
     */
    public function markOrderInvoicesAsEmailSent(array $orderInvoiceIds): void;
}
