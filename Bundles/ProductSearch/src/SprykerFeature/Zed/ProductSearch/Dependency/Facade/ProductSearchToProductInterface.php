<?php

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

interface ProductSearchToProductInterface
{
    /**
     * @param array $productsData
     *
     * @return array
     */
    public function buildProducts(array $productsData);
}
