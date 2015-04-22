<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
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
}
