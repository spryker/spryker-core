<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ProductOptionCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getDependencyContainer()->createProductOptionOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
