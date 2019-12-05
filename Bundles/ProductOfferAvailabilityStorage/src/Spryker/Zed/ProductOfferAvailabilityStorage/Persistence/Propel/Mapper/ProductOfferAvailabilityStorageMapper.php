<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class ProductOfferAvailabilityStorageMapper implements ProductOfferAvailabilityStorageMapperInterface
{
    public const COL_ALIAS_STORE_NAME = 'store_name';
    public const COL_ALIAS_SKU = 'sku';
    public const COL_ALIAS_QUANTITY = 'quantity';
    public const COL_ALIAS_ID_STORE = 'id_store';
    public const COL_ALIAS_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @param array $productOfferAvailabilityRequestData
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer
     */
    public function mapProductOfferAvailabilityRequestDataToRequestTransfer(
        array $productOfferAvailabilityRequestData,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ProductOfferAvailabilityRequestTransfer {
        return $productOfferAvailabilityRequestTransfer
            ->setSku($productOfferAvailabilityRequestData[static::COL_ALIAS_SKU])
            ->setQuantity($productOfferAvailabilityRequestData[static::COL_ALIAS_QUANTITY] ?? null)
            ->setProductOfferReference($productOfferAvailabilityRequestData[static::COL_ALIAS_PRODUCT_OFFER_REFERENCE])
            ->setStore(
                (new StoreTransfer())
                    ->setIdStore($productOfferAvailabilityRequestData[static::COL_ALIAS_ID_STORE])
                    ->setName($productOfferAvailabilityRequestData[static::COL_ALIAS_STORE_NAME])
            );
    }
}
