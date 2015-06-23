<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AvailabilityCheckoutConnector\Communication\Plugin;

use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckAvailabilityPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\AvailabilityCheckoutConnector\Communication\AvailabilityCheckoutConnectorDependencyContainer as DependencyContainer;

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

}
