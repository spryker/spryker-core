<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOptionExporter\Business\Model\ExportProcessorInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToLocaleInterface;
use SprykerFeature\Zed\ProductOptionExporter\ProductOptionExporterDependencyProvider;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionExporterBusiness;


/**
 * @method ProductOptionExporterBusiness getFactory()
 */
class ProductOptionExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ExportProcessorInterface
     */
    public function getProcessorModel()
    {
        return $this->getFactory()->createModelExportProcessor(
            $this->getLocator()->productOption()->queryContainer(),
            $this->getProductFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return ProductOptionExporterToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return ProductOptionExporterToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }
}
