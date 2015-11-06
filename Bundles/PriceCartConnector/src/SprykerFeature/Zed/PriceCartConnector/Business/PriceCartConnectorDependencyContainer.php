<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PriceCartConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\PriceCartConnectorConfig;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 * @method PriceCartConnectorBusiness getFactory()
 * @method PriceCartConnectorConfig getConfig()
 */
class PriceCartConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param null $grossPriceType
     *
     * @return Manager\PriceManager
     */
    public function createPriceManager($grossPriceType = null)
    {
        if ($grossPriceType === null) {
            $bundleConfig = $this->getConfig();
            $grossPriceType = $bundleConfig->getGrossPriceType();
        }

        return $this->getFactory()->createManagerPriceManager($this->getPriceFacade(), $grossPriceType);
    }

    /**
     * @return PriceFacade
     */
    protected function getPriceFacade()
    {
        return $this->getLocator()->price()->facade();
    }

}
