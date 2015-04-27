<?php

namespace SprykerFeature\Zed\Stock\Dependency\Facade;

use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

interface StockToProductInterface
{
    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getAbstractProductIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku);
}
