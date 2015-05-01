<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PriceCartConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use SprykerFeature\Zed\PriceCartConnector\PriceCartConnectorConfig;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 * @method PriceCartConnectorBusiness getFactory()
 * @method PriceCartConnectorConfig getConfig()
 */
class PriceCartConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return PriceManagerInterface
     */
    public function createPriceManager()
    {
        $bundleConfig = $this->getConfig();

        return $this->getFactory()->createManagerPriceManager($this->getPriceFacade(), $bundleConfig->getGrossPriceType());
    }

    /**
     * @return PriceFacade
     */
    protected function getPriceFacade()
    {
        return $this->getLocator()->price()->facade();
    }
}
