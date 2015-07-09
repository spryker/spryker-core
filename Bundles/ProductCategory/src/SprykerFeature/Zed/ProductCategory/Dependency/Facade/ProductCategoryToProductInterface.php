<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

interface ProductCategoryToProductInterface
{

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku);

}
