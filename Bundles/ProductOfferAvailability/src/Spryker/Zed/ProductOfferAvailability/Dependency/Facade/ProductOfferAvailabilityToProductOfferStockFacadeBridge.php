<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;

class ProductOfferAvailabilityToProductOfferStockFacadeBridge implements ProductOfferAvailabilityToProductOfferStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface
     */
    protected $productOfferStockFacade;

    /**
     * @param \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface $productOfferStockFacade
     */
    public function __construct($productOfferStockFacade)
    {
        $this->productOfferStockFacade = $productOfferStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal
    {
        return $this->productOfferStockFacade->getProductOfferStock($productOfferStockRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): bool
    {
        return $this->productOfferStockFacade->isProductOfferNeverOutOfStock($productOfferStockRequestTransfer);
    }
}
