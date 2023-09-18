<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;

interface ProductOfferAvailabilityToProductOfferStockFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function getProductOfferStockResult(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ProductOfferStockResultTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>|null
     */
    public function findProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ?ArrayObject;
}
