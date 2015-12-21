<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency\Facade;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

class WishlistToProductBridge implements WishlistToProductInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return ConcreteProductTransfer
     */
    public function getConcreteProduct($concreteSku)
    {
        return $this->productFacade->getConcreteProduct($concreteSku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->productFacade->getConcreteProductIdBySku($sku);
    }


}
