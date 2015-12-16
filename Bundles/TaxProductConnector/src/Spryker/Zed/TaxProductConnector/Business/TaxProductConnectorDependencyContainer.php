<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

/**
 * @method TaxProductConnectorConfig getConfig()
 */
class TaxProductConnectorDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return TaxChangePluginInterface
     */
    public function getTaxChangeTouchPlugin()
    {
        return new TaxChangeTouchPlugin(
            $this->getProductFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return TaxProductConnectorQueryContainerInterface
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->taxProductConnector()->queryContainer();
    }

    /**
     * @return TaxProductConnectorToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

}
