<?php

namespace SprykerFeature\Zed\Availability\Business;

use SprykerFeature\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityToCheckoutConnectorFacadeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class AvailabilityFacade extends AbstractFacade implements AvailabilityToCheckoutConnectorFacadeInterface
{

    /** @var AvailabilityDependencyContainer */
    protected $dependencyContainer;

    /**
     * @param string $sku
     * @param int $quantity
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getDependencyContainer()->getSellableModel()->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getDependencyContainer()->getSellableModel()->calculateStockForProduct($sku);
    }

}
