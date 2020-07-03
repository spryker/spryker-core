<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business;

interface MerchantProductStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets idMerchantProducts from eventTransfers.
     * - Finds merhant product by idMerchantProducts.
     * - Extracts $idProductAbstracts from merchant products.
     * - Runs product storage publisher with $idProductAbstracts.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeMerchantProductCollectionByIdProductAbstractMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets idProductAbstracts from eventTransfers.
     * - Runs product storage publisher with $idProductAbstracts.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteMerchantProductStorageCollectionByIdProductAbstractEvents(array $eventTransfers): void;
}
