<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Dependency\Facade;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantSalesReturnToMerchantSalesOrderFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(
        MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
    ): ?MerchantOrderTransfer;
}
