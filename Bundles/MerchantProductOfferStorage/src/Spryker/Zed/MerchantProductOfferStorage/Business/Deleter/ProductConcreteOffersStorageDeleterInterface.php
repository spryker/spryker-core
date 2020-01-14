<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Deleter;

interface ProductConcreteOffersStorageDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteByProductSkuEvents(array $eventTransfers): void;

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function deleteByProductSkus(array $productSkus): void;

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return void
     */
    public function deleteByProductOfferReferencesAndStore(
        array $productOfferReferences,
        string $storeName
    ): void;
}
