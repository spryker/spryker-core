<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method OmsCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class OmsCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getDependencyContainer()->createOmsOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

}
