<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AvailabilityCartConnector\Communication\Plugin;

use SprykerFeature\Zed\AvailabilityCartConnector\Communication\AvailabilityCartConnectorDependencyContainer as DependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckAvailabilityPluginInterface;

/**
 * @method DependencyContainer getDependencyContainer()
 */
class CheckAvailabilityPlugin extends AbstractPlugin implements CheckAvailabilityPluginInterface
{
    /**
     * @param string $sku
     * @param int $quantity
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->getDependencyContainer()->getAvailabilityFacade()->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getDependencyContainer()->getAvailabilityFacade()->calculateStockForProduct($sku);
    }

}
