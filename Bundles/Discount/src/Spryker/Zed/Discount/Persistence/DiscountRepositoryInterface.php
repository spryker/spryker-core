<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Propel\Runtime\Collection\ObjectCollection;

interface DiscountRepositoryInterface
{
    /**
     * @param string[] $codes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findVouchersExceedingUsageLimitByCodes(array $codes): ObjectCollection;
}
