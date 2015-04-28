<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterConnectorBusiness;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder\ProductKeyBuilder;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\UrlKeyBuilder;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Builder\ProductUrlKeyBuilder;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Decider\ProductExportDeciderInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor\ProductUrlMapProcessorInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade\ProductFrontendExporterToProductInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\ProductFrontendExporterConnectorConfig;

/**
 * @method ProductFrontendExporterConnectorBusiness getFactory()
 * @method ProductFrontendExporterConnectorConfig getConfig()
 */
class ProductFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
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
        return $this->getFactory()->createBuilderResourceKeyBuilder();
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
