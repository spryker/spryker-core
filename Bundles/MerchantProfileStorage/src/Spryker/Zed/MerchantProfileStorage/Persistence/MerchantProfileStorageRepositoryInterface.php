<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface MerchantProfileStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function getFilteredMerchantProfileStorageTransfers(FilterTransfer $filterTransfer, array $merchantIds = []): array;
}
