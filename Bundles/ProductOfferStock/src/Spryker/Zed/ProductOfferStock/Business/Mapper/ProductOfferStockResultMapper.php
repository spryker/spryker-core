<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
use Spryker\DecimalObject\Decimal;

class ProductOfferStockResultMapper implements ProductOfferStockResultMapperInterface
{
    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ProductOfferStockTransfer> $productOfferStockTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[] $productOfferStockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function convertProductOfferStockTransfersToProductOfferStockResultTransfer(
        ArrayObject $productOfferStockTransfers
    ): ProductOfferStockResultTransfer {
        $productOfferStockResultTransfer = new ProductOfferStockResultTransfer();

        $productOfferStockResultTransfer->setIsNeverOutOfStock(false);
        $totalQuantity = new Decimal(0);

        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            /** @var \Spryker\DecimalObject\Decimal $quantity */
            $quantity = $productOfferStockTransfer->getQuantity();

            $totalQuantity = $totalQuantity->add($quantity);
            if ($productOfferStockTransfer->getIsNeverOutOfStock()) {
                $productOfferStockResultTransfer->setIsNeverOutOfStock(true);
            }
        }
        $productOfferStockResultTransfer->setQuantity($totalQuantity);

        return $productOfferStockResultTransfer;
    }
}
