<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

interface DiscountCreateAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function createDiscount(DiscountConfiguratorTransfer $discountConfiguratorTransfer): DiscountConfiguratorResponseTransfer;
}
