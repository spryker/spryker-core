<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;

class ProductOfferStockMapper
{
    /**
     * @param \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock $productOfferStockEntity
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function mapProductOfferStockEntityToProductOfferStockTransfer(
        SpyProductOfferStock $productOfferStockEntity,
        ProductOfferStockTransfer $productOfferStockTransfer
    ): ProductOfferStockTransfer {
        $productOfferStockTransfer->fromArray($productOfferStockEntity->toArray(), true);

        return $productOfferStockTransfer;
    }
}
