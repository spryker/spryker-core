<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 */
class QuoteToSaveOrderMapperCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalOrder` to be set.
     * - Copies items from `QuoteTransfer` to `SaveOrderTransfer`.
     * - Maps the original order from `QuoteTransfer.originalOrder` to `SaveOrderTransfer`.
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
        $orderTransfer = $quoteTransfer->getOriginalOrderOrFail();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem(clone $itemTransfer);
        }

        $saveOrderTransfer->fromArray($orderTransfer->toArray(), true);
    }
}
