<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;

class ProductOptionExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductOptionExporterFacade
     */
    public function getFacade()
    {
        return $this->getLocator()->productOptionExporter()->facade();
    }

    /**
     * @return ProductOptionExporterFacade
     */
    public function getProductOptionProcessor()
    {
        return $this->getFacade();
    }
}

