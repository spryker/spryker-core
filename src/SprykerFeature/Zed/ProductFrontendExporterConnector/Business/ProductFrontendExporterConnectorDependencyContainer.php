<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterConnectorBusiness;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Decider\ProductExportDeciderInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade\ProductFrontendExporterToProductInterface;

/**
 * @method ProductFrontendExporterConnectorBusiness getFactory()
 */
class ProductFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var ProductFrontendExporterConnectorSettings
     */
    //TODO remove state
    protected $settings;

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
            $this->getSettings()->getPercentageOfFaultToleranceForExport()
        );
    }

    /**
     * @return ProductFrontendExporterConnectorSettings
     */
    protected function getSettings()
    {
        if (is_null($this->settings)) {
            $this->settings = $this->getFactory()->createProductFrontendExporterConnectorSettings();
        }

        return $this->settings;
    }
}
