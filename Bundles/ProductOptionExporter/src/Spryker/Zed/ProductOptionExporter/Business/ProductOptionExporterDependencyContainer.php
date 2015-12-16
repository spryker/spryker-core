<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Spryker\Zed\ProductOptionExporter\Business\Model\ExportProcessor;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionExporter\Business\Model\ExportProcessorInterface;
use Spryker\Zed\ProductOptionExporter\ProductOptionExporterDependencyProvider;

class ProductOptionExporterDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return ExportProcessorInterface
     */
    public function getProcessorModel()
    {
        return new ExportProcessor(
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT_OPTION),
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT)
        );
    }

}
