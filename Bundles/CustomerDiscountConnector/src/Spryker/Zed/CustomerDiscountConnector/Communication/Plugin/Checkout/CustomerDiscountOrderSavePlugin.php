<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerDiscountConnector\Communication\CustomerDiscountConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorBusinessFactory getBusinessFactory()
 */
class CustomerDiscountOrderSavePlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Saves customer discounts to the persistent storage.
     * - Requires `QuoteTransfer.customer` to be set.
     * - Stores the relationship between customer and discount.
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
        $this->getBusinessFactory()
            ->createCustomerDiscountSaver()
            ->saveCustomerDiscounts($quoteTransfer);
    }
}
