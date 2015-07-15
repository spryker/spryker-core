<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * Class ProductCategoryDependencyContainer
 */
class ProductCategoryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }

}
