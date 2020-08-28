<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Creator;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface MerchantOrderCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function createMerchantOrderCollection(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer;
}
