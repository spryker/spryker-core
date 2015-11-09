<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
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
    public function hydrateOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $checkoutRequest)
    {
        $this->getDependencyContainer()->createOrderHydrator()->hydrateOrder($orderTransfer, $checkoutRequest);
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getDependencyContainer()->createDiscountSaver()->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

}
