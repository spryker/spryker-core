<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\DiscountTransfer;

interface DistributorInterface
{

    /**
     * @param array $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     */
    public function distribute(array $discountableObjects, DiscountTransfer $discountTransfer);

}
