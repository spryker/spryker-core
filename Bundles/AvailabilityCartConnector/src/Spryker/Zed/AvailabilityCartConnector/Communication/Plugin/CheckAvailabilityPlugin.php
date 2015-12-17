<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCartConnector\Communication\Plugin;

use Spryker\Zed\AvailabilityCartConnector\Communication\AvailabilityCartConnectorCommunicationFactory as CommunicationFactory;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CommunicationFactory getFactory()
 */
class CheckAvailabilityPlugin extends AbstractPlugin
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getFactory()->getAvailabilityFacade()->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()->getAvailabilityFacade()->calculateStockForProduct($sku);
    }

}
