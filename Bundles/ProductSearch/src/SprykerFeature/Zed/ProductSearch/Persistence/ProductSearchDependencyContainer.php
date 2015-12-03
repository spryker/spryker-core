<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductSearchDependencyContainer extends AbstractPersistenceDependencyContainer
{

    /**
     * @return ProductSearchQueryExpanderInterface
     */
    public function createProductSearchQueryExpander()
    {
        return new ProductSearchQueryExpander(
            $this->createProductQueryContainer()
        );
    }

    /**
     * @return ProductQueryContainerInterface
     */
    protected function createProductQueryContainer()
    {
        return $this->getLocator()->product()->queryContainer();
    }

}
