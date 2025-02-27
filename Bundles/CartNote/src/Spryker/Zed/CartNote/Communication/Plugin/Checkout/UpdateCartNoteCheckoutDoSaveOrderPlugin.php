<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartNote\Business\CartNoteFacadeInterface getFacade()
 */
class UpdateCartNoteCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Finds order by `SaveOrderTransfer.idSalesOrder`.
     * - Updates order's cart note with the cart note provided in `QuoteTransfer.cartNote`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFacade()->saveOrderCartNote($quoteTransfer, $saveOrderTransfer, true);
    }
}
