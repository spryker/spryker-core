<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferServicePointStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<string> $productOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferServiceStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $productOfferReferences = []): array;
}
