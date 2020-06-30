<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;

interface ProductConcreteDefaultProductOfferInterface
{
    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string[]
     */
    public function getProductOfferReferences(
        array $productOfferStorageTransfers,
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): array;
}
