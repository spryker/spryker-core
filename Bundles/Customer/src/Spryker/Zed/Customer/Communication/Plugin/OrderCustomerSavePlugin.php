<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use \Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerOrderSavePlugin instead
 * Will be removed with the next major.
 *
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class OrderCustomerSavePlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->saveCustomerForOrder($quoteTransfer, $checkoutResponse);
    }
}
