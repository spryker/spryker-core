<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Dependency\Facade;

use Spryker\Zed\Product\Business\Exception\MissingProductException;

interface PriceToProductInterface
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
    public function getConcreteProductIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku);

}
