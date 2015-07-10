<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * Class ProductCategorySearchDependencyContainer
 */
class ProductCategorySearchDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Processor\ProductCategorySearchProcessor
     */
    public function createProductCategorySearchProcessor()
    {
        return $this->getFactory()->create('Processor\\ProductCategorySearchProcessor');
    }

}
