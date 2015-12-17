<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductSearchPersistenceFactory extends AbstractPersistenceFactory
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
