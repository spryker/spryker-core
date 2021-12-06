<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Creator;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

interface DiscountVoucherPoolCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int|null
     */
    public function createDiscountVoucherPool(DiscountConfiguratorTransfer $discountConfiguratorTransfer): ?int;
}
