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
    public function deleteCollectionByProductOfferReferenceEvents(array $eventTransfers): void;

    /**
     * @param string[] $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteCollectionByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void;
}
