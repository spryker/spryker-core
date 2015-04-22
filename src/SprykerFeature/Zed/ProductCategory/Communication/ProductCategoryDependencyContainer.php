<?php

namespace SprykerFeature\Zed\ProductCategory\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * Class ProductCategoryDependencyContainer
 * @package SprykerFeature\Zed\ProductCategory\Communication
 */
class ProductCategoryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }
}
