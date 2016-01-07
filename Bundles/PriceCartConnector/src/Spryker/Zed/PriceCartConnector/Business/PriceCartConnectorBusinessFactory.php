<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorDependencyProvider;

/**
 * @method PriceCartConnectorBusinessFactory getFactory()
 * @method PriceCartConnectorConfig getConfig()
 */
class PriceCartConnectorBusinessFactory extends AbstractBusinessFactory
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

        return new PriceManager($this->getPriceFacade(), $grossPriceType);
    }

    /**
     * @return PriceCartToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_PRICE);
    }

}
