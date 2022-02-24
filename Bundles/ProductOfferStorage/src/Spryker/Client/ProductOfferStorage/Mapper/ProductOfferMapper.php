<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class ProductOfferMapper implements ProductOfferMapperInterface
{
    /**
     * @param array<mixed> $productOfferStorageData
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function mapProductOfferStorageDataToProductOfferStorageTransfer(
        array $productOfferStorageData,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer {
        $productOfferStorageTransfer->fromArray($productOfferStorageData, true);

        return $productOfferStorageTransfer;
    }
}
