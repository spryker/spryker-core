<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

interface MerchantOmsRepositoryInterface
{
    /**
     * @param int $stateId
     *
     * @return int[]
     */
    public function getMerchantOrderItemIdsByStateId(int $stateId): array;
}
