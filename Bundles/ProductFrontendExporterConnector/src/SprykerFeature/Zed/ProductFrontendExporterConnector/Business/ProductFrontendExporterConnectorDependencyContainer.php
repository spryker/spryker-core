<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterConnectorBusiness;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Decider\ProductExportDeciderInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade\ProductFrontendExporterToProductInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\ProductFrontendExporterConnectorConfig;

/**
 * @method ProductFrontendExporterConnectorBusiness getFactory()
 * @method ProductFrontendExporterConnectorConfig getConfig()
 */
class ProductFrontendExporterConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Processor\ProductProcessor
     */
    public function getProductProcessor()
    {
        return $this->getFactory()->createProcessorProductProcessor(
            $this->getProductFacade(),
            $this->getResourceKeyBuilder()
        );
    }

    /**
     * @return ProductFrontendExporterToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getResourceKeyBuilder()
    {
        return $this->getFactory()->createBuilderAbstractProductResourceKeyBuilder();
    }

    /**
     * @return ProductExportDeciderInterface
     */
    public function getProductExportDecider()
    {
        return $this->getFactory()->createDeciderProductExportDecider(
            $this->getConfig()->getPercentageOfFaultToleranceForExport()
        );
    }

}
