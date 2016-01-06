<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;
use Generated\Shared\Transfer\ConcreteProductTransfer;

class WishlistToProductBridge implements WishlistToProductInterface
{

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @param ProductFacade $productFacade
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
