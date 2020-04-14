<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;

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
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ProductOfferStockTransfer
    {
        return $this->productOfferStockFacade->getProductOfferStock($productOfferStockRequestTransfer);
    }
}
