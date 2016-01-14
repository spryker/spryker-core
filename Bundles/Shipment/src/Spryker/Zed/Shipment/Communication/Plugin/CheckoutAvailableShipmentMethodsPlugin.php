<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * @method ShipmentFacade getFacade()
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{
    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->getAvailableMethods($quoteTransfer);
    }

}
