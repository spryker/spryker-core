<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Dependency\Facade;

use Spryker\Zed\Product\Business\Exception\MissingProductException;

interface StockToProductInterface
{

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

}
