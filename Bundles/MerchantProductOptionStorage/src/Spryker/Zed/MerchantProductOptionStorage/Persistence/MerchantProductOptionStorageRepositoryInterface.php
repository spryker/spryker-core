<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Persistence;

interface MerchantProductOptionStorageRepositoryInterface
{
    /**
     * @param int[] $merchantProductOptionGroupIds
     *
     * @return int[]
     */
    public function getAbstractProductIdsByMerchantProductOptionGroupIds(array $merchantProductOptionGroupIds): array;
}
