<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Business;

use Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityToCheckoutConnectorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method AvailabilityDependencyContainer getBusinessFactory()
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
        return $this->getBusinessFactory()->getSellableModel()->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getBusinessFactory()->getSellableModel()->calculateStockForProduct($sku);
    }

}
