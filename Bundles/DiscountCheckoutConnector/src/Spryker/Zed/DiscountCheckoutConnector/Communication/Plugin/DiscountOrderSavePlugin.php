<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use Spryker\Zed\DiscountCheckoutConnector\Business\DiscountCheckoutConnectorFacade;
use Spryker\Zed\DiscountCheckoutConnector\Communication\DiscountCheckoutConnectorCommunicationFactory;

/**
 * @method DiscountCheckoutConnectorFacade getFacade()
 * @method DiscountCheckoutConnectorCommunicationFactory getFactory()
 */
class DiscountOrderSavePlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->saveDiscounts($orderTransfer, $checkoutResponse);
    }

}
