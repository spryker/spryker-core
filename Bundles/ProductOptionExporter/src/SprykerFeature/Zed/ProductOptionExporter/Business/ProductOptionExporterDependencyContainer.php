<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionExporterBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductOptionExporter\Business\Model\ExportProcessorInterface;
use SprykerFeature\Zed\ProductOptionExporter\ProductOptionExporterDependencyProvider;

/**
 * @method ProductOptionExporterBusiness getFactory()
 */
class ProductOptionExporterDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ExportProcessorInterface
     */
    public function getProcessorModel()
    {
        return $this->getFactory()->createModelExportProcessor(
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT_OPTION),
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT)
        );
    }

}
