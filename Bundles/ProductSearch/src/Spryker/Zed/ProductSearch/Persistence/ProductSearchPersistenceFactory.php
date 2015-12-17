<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method ProductQueryContainer getQueryContainer()
 */
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
        return $this->getQueryContainer();
    }

}
