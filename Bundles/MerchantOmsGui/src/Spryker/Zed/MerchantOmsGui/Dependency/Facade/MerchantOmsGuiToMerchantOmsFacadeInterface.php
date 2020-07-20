<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOmsGui\Dependency\Facade;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface MerchantOmsGuiToMerchantOmsFacadeInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
     */
    public function findCurrentStateByIdSalesOrderItem(int $idSalesOrderItem): ?StateMachineItemTransfer;
}
