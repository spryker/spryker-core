<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\ProductCategoryFrontendExporterConnectorFacade;

/**
 * Class ProductCategoryFrontendExporterConnectorDependencyContainer
 */
class ProductCategoryFrontendExporterConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
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
