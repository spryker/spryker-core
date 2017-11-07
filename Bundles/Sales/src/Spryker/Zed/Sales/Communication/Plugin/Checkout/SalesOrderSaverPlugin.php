<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Requires Checkout ^4.0.0
 *
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class SalesOrderSaverPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFacade()->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
    }
}
