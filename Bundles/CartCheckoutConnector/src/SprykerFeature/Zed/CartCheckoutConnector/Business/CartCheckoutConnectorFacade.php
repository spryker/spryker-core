<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Business;

use Generated\Shared\CartCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CartCheckoutConnector\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CartCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CartCheckoutConnectorFacade extends AbstractFacade
{

    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $this->getDependencyContainer()->createCartOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
