<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionCheckoutConnectorBusinessFactory getFactory()
 */
class ProductOptionCheckoutConnectorFacade extends AbstractFacade implements ProductOptionCheckoutConnectorFacadeInterface
{

    /**
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getFactory()->createProductOptionOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
