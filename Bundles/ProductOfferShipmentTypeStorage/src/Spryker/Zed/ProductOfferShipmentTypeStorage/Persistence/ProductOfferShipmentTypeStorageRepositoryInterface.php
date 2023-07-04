<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferShipmentTypeStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<string> $productOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferReferences = []
    ): array;
}
