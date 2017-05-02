<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Invoice\Business;

use Generated\Shared\Transfer\OrderTransfer;


/**
 * @method \Spryker\Zed\Invoice\Business\InvoiceBusinessFactory getFactory()
 */
interface InvoiceFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function buildInvoice(OrderTransfer $orderTransfer);
}
