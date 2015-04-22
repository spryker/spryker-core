<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\ProductCategoryFrontendExporterConnectorFacade;

/**
 * Class ProductCategoryFrontendExporterConnectorDependencyContainer
 * @package SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication
 */
class ProductCategoryFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }

    /**
     * @return ProductCategoryFrontendExporterConnectorFacade
     */
    public function getProductCategoryFrontendExporterFacade()
    {
        return $this->getLocator()->productCategoryFrontendExporterConnector()->facade();
    }
}
