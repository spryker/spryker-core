<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

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
