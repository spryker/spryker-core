<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CartCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CartCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getDependencyContainer()->createCartOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
