<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * Class ProductCategorySearchDependencyContainer
 * @package SprykerFeature\Zed\ProductCategory\Business
 */
class ProductCategorySearchDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return Processor\ProductCategorySearchProcessor
     */
    public function createProductCategorySearchProcessor()
    {
        return $this->getFactory()->create('Processor\\ProductCategorySearchProcessor');
    }
}
