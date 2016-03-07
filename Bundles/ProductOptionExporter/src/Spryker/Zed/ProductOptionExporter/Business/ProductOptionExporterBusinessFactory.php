<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionExporter\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionExporter\Business\Model\ExportProcessor;
use Spryker\Zed\ProductOptionExporter\ProductOptionExporterDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionExporter\ProductOptionExporterConfig getConfig()
 */
class ProductOptionExporterBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOptionExporter\Business\Model\ExportProcessorInterface
     */
    public function createProcessorModel()
    {
        return new ExportProcessor(
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT_OPTION),
            $this->getProvidedDependency(ProductOptionExporterDependencyProvider::FACADE_PRODUCT)
        );
    }

}
