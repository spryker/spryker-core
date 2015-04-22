<?php

namespace SprykerFeature\Zed\PriceCartConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Communication\PriceCartConnectorDependencyContainer;
use SprykerFeature\Zed\Cart\Dependency\Plugin\GetPricePluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class GetPricePlugin extends AbstractPlugin implements GetPricePluginInterface
{
    /**
     * @param string $sku
     * @param null $priceType
     * @return int
     */
    public function getPrice($sku, $priceType = null)
    {
        $priceFacade = $this->getDependencyContainer()->getPriceFacade();
        return $priceFacade->getPriceBySku($sku, $priceType);
    }

}
