<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability\Business;

use SprykerFeature\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityToCheckoutConnectorFacadeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method AvailabilityDependencyContainer getDependencyContainer()
 */
class AvailabilityFacade extends AbstractFacade implements AvailabilityToCheckoutConnectorFacadeInterface
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getDependencyContainer()->getSellableModel()->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getDependencyContainer()->getSellableModel()->calculateStockForProduct($sku);
    }

}
