<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

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
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

}
