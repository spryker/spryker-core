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
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[] $productOfferStockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function mapProductOfferStockTransfersToProductOfferStockResultTransfer(
        ArrayObject $productOfferStockTransfers
    ): ProductOfferStockResultTransfer {
        $productOfferStockResultTransfer = new ProductOfferStockResultTransfer();
        $quantity = new Decimal(0);
        $isNeverOutOfStock = null;

        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
            $quantity = $quantity->add($productOfferStockTransfer->getQuantity());
            if ($productOfferStockTransfer->getIsNeverOutOfStock() && $productOfferStockTransfer->getIsNeverOutOfStock() !== null) {
                $isNeverOutOfStock = true;
            }
        }

        return $productOfferStockResultTransfer
            ->setQuantity($quantity)
            ->setIsNeverOutOfStock($isNeverOutOfStock);
    }
}
