<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategorySearch\Business\ProductCategorySearchFacade;

/**
 * Class ProductCategorySearchDependencyContainer
 * @package SprykerFeature\Zed\ProductCategorySearch\Communication
 */
class ProductCategorySearchDependencyContainer extends AbstractDependencyContainer
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
