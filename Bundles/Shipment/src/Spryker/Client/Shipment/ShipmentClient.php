<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Shipment;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Shipment\ShipmentFactory getFactory()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->getAvailableMethods($quoteTransfer);
    }

}
