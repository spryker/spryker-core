<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductSearchDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductSearchQueryExpanderInterface
     */
    public function createProductSearchQueryExpander()
    {
        return $this->getFactory()->createProductSearchQueryExpander(
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
