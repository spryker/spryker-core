<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;

/**
 * @method \Spryker\Zed\DiscountCheckoutConnector\Business\DiscountCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\DiscountCheckoutConnector\Communication\DiscountCheckoutConnectorCommunicationFactory getFactory()
 */
class DiscountOrderSavePlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->saveDiscounts($quoteTransfer, $checkoutResponse);
    }

}
