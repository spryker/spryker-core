<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOptionExporter\Business\Model\ExportProcessorInterface;
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
        return $this->getFactory()->createModelExportProcessor();
    }
}
