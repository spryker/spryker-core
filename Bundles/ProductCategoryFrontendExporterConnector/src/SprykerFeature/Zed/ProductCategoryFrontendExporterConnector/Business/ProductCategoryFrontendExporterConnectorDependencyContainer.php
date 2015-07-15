<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryFrontendExporterConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * Class ProductCategoryFrontendExporterDependencyContainer
 */
/**
 * @method ProductCategoryFrontendExporterConnectorBusiness getFactory()
 */
class ProductCategoryFrontendExporterConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Processor\ProductCategoryBreadcrumbProcessor
     */
    public function createProductCategoryBreadcrumbProcessor()
    {
        return $this->getFactory()->createProcessorProductCategoryBreadcrumbProcessor(
            $this->getLocator()->categoryExporter()->facade()
        );
    }

}
