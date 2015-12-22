<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency\Facade;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

interface WishlistToProductInterface
{

    /**
     * @param string $concreteSku
     *
     * @return ConcreteProductTransfer
     */
    public function getConcreteProduct($concreteSku);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku);

}
