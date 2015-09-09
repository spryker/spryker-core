<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\CheckoutResponseInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
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
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse)
    {
        $this->getDependencyContainer()->createDicountSaver()->saveDiscounts($orderTransfer, $checkoutResponse);
    }

}
