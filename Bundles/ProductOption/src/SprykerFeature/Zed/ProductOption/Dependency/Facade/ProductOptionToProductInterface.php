<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Dependency\Facade;

use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

interface ProductOptionToProductInterface
{

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
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdByConcreteSku($sku);

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

}
