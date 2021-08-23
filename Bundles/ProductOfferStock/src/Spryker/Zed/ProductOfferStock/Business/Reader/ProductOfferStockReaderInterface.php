<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;

interface ProductOfferStockReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function getProductOfferStockResult(
        ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
    ): ProductOfferStockResultTransfer;

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[]
     */
    public function getProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject;
}
