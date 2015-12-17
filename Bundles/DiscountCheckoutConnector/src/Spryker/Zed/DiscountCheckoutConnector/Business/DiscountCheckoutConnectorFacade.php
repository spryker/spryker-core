<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method DiscountCheckoutConnectorBusinessFactory getFactory()
 */
class DiscountCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param $orderTransfer
     * @param $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $this->getFactory()->createOrderHydrator()->hydrateOrder($orderTransfer, $checkoutRequest);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveDiscounts(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()->createDiscountSaver()->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

}
