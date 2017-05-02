<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Invoice\Business\InvoiceFacadeInterface;

class SalesToInvoiceBridge implements SalesToInvoiceInterface
{
    /**
     * @var \Spryker\Zed\Invoice\Business\InvoiceFacadeInterface
     */
    protected $invoiceFacade;

    /**
     * @param \Spryker\Zed\Invoice\Business\InvoiceFacadeInterface $invoiceFacade
     */
    public function __construct(InvoiceFacadeInterface $invoiceFacade)
    {
        $this->invoiceFacade = $invoiceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function buildInvoice(OrderTransfer $orderTransfer)
    {
        return $this->invoiceFacade->buildInvoice($orderTransfer);
    }


}
