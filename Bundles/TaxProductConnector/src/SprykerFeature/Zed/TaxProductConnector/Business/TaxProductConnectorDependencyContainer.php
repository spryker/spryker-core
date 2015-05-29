<?php

namespace SprykerFeature\Zed\TaxProductConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TaxProductConnectorBusiness;
use SprykerFeature\Zed\TaxProductConnector\TaxProductConnectorConfig;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;
use SprykerFeature\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;
use SprykerFeature\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

/**
 * @method TaxProductConnectorBusiness getFactory()
 * @method TaxProductConnectorConfig getConfig()
 */
class TaxProductConnectorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TaxChangePluginInterface
     */
    public function getTaxChangeTouchPlugin()
    {
        return $this->getFactory()->createPluginTaxChangeTouchPlugin(
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
