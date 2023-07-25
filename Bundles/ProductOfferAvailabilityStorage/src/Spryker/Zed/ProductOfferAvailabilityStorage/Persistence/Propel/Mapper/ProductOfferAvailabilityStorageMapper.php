<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class ProductOfferAvailabilityStorageMapper implements ProductOfferAvailabilityStorageMapperInterface
{
    /**
     * @param array<string, mixed> $productOfferAvailabilityRequestData
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer
     */
    public function mapProductOfferAvailabilityRequestDataToRequestTransfer(
        array $productOfferAvailabilityRequestData,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ProductOfferAvailabilityRequestTransfer {
        $productOfferAvailabilityRequestTransfer = $productOfferAvailabilityRequestTransfer
            ->setSku($productOfferAvailabilityRequestData[static::COL_ALIAS_SKU])
            ->setQuantity($productOfferAvailabilityRequestData[static::COL_ALIAS_QUANTITY] ?? null)
            ->setProductOfferReference($productOfferAvailabilityRequestData[static::COL_ALIAS_PRODUCT_OFFER_REFERENCE])
            ->setStock($this->mapProductOfferAvailabilityRequestDataToStockTransfer($productOfferAvailabilityRequestData, new StockTransfer()));

        if ($productOfferAvailabilityRequestData[static::COL_ALIAS_ID_STORE]) {
            $productOfferAvailabilityRequestTransfer->setStore(
                $this->mapProductOfferAvailabilityRequestDataToStoreTransfer($productOfferAvailabilityRequestData, new StoreTransfer()),
            );
        }

        return $productOfferAvailabilityRequestTransfer;
    }

    /**
     * @param array<string, mixed> $productOfferAvailabilityRequestData
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapProductOfferAvailabilityRequestDataToStockTransfer(
        array $productOfferAvailabilityRequestData,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer->setIdStock($productOfferAvailabilityRequestData[static::COL_ALIAS_ID_STOCK]);
    }

    /**
     * @param array $productOfferAvailabilityRequestData
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapProductOfferAvailabilityRequestDataToStoreTransfer(
        array $productOfferAvailabilityRequestData,
        StoreTransfer $storeTransfer
    ): StoreTransfer {
        return $storeTransfer
            ->setIdStore($productOfferAvailabilityRequestData[static::COL_ALIAS_ID_STORE])
            ->setName($productOfferAvailabilityRequestData[static::COL_ALIAS_STORE_NAME]);
    }
}
