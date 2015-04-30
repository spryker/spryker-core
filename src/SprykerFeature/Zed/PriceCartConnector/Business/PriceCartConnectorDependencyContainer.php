<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PriceCartConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 * @method PriceCartConnectorBusiness getFactory()
 */
class PriceCartConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return PriceManagerInterface
     */
    public function createPriceManager()
    {
        $settings = $this->getSettings();

        return $this->getFactory()->createManagerPriceManager($this->getPriceFacade(), $settings->getGrossPriceType());
    }

    /**
     * @return PriceCartConnectorSettings
     */
    protected function getSettings()
    {
        return $this->getFactory()->createPriceCartConnectorSettings();
    }

    /**
     * @return PriceFacade
     */
    protected function getPriceFacade()
    {
        return $this->getLocator()->price()->facade();
    }
}
