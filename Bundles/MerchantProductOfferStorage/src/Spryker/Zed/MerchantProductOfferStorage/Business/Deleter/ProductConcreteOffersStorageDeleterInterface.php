<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Deleter;

interface ProductConcreteOffersStorageDeleterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductSkuEvents(array $eventTransfers): void;

    /**
     * @param array<string> $productSkus
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteCollectionByProductSkus(array $productSkus, ?string $storeName = null): void;
}
