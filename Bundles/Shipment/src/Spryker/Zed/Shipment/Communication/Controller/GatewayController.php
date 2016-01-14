<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsTransfer
     */
    public function getAvailableMethodsAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->getAvailableMethods($quoteTransfer);
    }

}
