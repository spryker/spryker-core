<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGui\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;

interface ProductOfferStockGuiToProductOfferStockFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[]
     */
    public function getProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject;
}
