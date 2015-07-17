<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategorySearch\Business\ProductCategorySearchFacade;

/**
 * Class ProductCategorySearchDependencyContainer
 */
class ProductCategorySearchDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductCategorySearchFacade
     */
    public function getProductCategorySearchFacade()
    {
        return $this->getLocator()->productCategorySearch()->facade();
    }

    /**
     * @return ProductCategoryQueryContainer
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }

}
