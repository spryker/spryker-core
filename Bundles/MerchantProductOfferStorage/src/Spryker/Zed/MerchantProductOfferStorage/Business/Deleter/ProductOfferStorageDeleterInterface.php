<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Deleter;

interface ProductOfferStorageDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteByProductOfferReferenceEvents(array $eventTransfers): void;

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteByProductOfferReferences(array $productOfferReferences): void;

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferReferencesAndStore(
        array $productOfferReferences,
        string $storeName
    ): void;
}
