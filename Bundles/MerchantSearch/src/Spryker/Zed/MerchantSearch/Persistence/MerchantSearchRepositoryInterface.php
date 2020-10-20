<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface MerchantSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getMerchantSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $merchantIds = []): array;
}
