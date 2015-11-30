<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method DiscountCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param $orderTransfer
     * @param $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $this->getDependencyContainer()->createOrderHydrator()->hydrateOrder($orderTransfer, $checkoutRequest);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function saveDiscounts(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getDependencyContainer()->createDiscountSaver()->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

}
