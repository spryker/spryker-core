<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface WishlistToProductInterface
{

    /**
     * @param string $concreteSku
     *
     * @return ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

}
