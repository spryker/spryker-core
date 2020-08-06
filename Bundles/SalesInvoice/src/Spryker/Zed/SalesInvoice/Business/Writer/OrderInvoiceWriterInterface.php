<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Writer;

use Generated\Shared\Transfer\OrderInvoiceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderInvoiceWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceResponseTransfer
     */
    public function generateOrderInvoice(OrderTransfer $orderTransfer): OrderInvoiceResponseTransfer;
}
