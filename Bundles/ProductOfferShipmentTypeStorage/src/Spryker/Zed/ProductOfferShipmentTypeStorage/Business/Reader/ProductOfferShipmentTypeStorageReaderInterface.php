<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferShipmentTypeStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferIds = []
    ): array;
}
