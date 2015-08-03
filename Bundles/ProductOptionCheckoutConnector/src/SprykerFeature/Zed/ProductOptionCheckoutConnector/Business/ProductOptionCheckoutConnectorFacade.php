<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\ProductOptionCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ProductOptionCheckoutConnectorFacade extends AbstractFacade
{

    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $this->getDependencyContainer()->createProductOptionOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
