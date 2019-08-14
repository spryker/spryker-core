<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

interface DiscountRepositoryInterface
{
    /**
     * @param string[] $codes
     *
     * @return string[]
     */
    public function findVoucherCodesExceedingUsageLimit(array $codes): array;
}
