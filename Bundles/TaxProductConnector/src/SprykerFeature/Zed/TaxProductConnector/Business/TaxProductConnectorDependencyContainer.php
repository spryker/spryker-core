<?php

namespace SprykerFeature\Zed\TaxProductConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TaxProductConnectorBusiness;
use SprykerFeature\Zed\TaxProductConnector\TaxProductConnectorConfig;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use SprykerFeature\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductInterface;

/**
 * @method TaxProductConnectorBusiness getFactory()
 * @method TaxProductConnectorConfig getConfig()
 */
class TaxProductConnectorDependencyContainer extends AbstractDependencyContainer
{

    public function getTaxChangeTouchPlugin()
    {
        return $this->getFactory()->createPluginTaxChangeTouchPlugin(
            $this->getProductFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return TaxProductConnectorQueryContainer
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
