<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

interface MinimumOrderValueRepositoryInterface
{
    /**
     * @param int $storeId
     * @param int $currencyId
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(int $storeId, int $currencyId): array;
}
