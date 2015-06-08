<?php

namespace SprykerFeature\Zed\ProductOptions\Dependency\Facade;

use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

interface ProductOptionsToProductInterface
{

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku);
}
